<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $registrationController;

    public function __construct(TeamRegistrationController $registrationController)
    {
        $this->registrationController = $registrationController;
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'nullable|in:momo,bank,card',
            'payer_email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $request->merge(['payment_method' => $request->payment_method ?: 'card']);

        $paymentDetails = $this->validatePaymentDetails($request);
        if ($paymentDetails instanceof \Illuminate\Http\JsonResponse) {
            return $paymentDetails;
        }

        try {
            DB::beginTransaction();

            $team = $this->registrationController->createTeamFromSession();

            if (!$team) {
                DB::rollBack();

                return response()->json([
                    'error' => 'Registration data not found. Please start over.',
                ], 400);
            }

            $team->loadMissing('primaryOwner');

            $payerEmail = $request->input('payer_email') ?: optional($team->primaryOwner)->email;

            if ($request->payment_method === 'card' && empty($payerEmail)) {
                DB::rollBack();

                return response()->json([
                    'error' => 'A valid payer email is required to initialize Paystack.',
                ], 422);
            }

            $payment = Payment::create([
                'team_id' => $team->id,
                'reference_code' => Payment::generateReferenceCode(),
                'payment_method' => $request->payment_method,
                'amount' => 502.00,
                'status' => $this->getInitialPaymentStatus($request->payment_method),
                'payment_details' => $paymentDetails,
                'notes' => 'Registration fee for ' . $team->team_name,
            ]);

            $processingResult = $this->processByMethod($payment->fresh('team.primaryOwner'), $request, $payerEmail);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $processingResult['message'],
                'reference_code' => $team->reference_code,
                'payment_reference' => $payment->reference_code,
                'team_id' => $team->id,
                'payment_status' => $payment->fresh()->status,
                'redirect_url' => $processingResult['redirect_url'] ?? null,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Payment processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function validatePaymentDetails(Request $request)
    {
        $paymentDetails = [];

        if ($request->payment_method === 'momo') {
            $validator = Validator::make($request->all(), [
                'momo_network' => 'required|in:mtn,voda,airtel',
                'momo_number' => 'required|regex:/^[0-9]{10}$/',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $paymentDetails = [
                'network' => $request->momo_network,
                'number' => $request->momo_number,
                'network_display' => $this->getNetworkDisplayName($request->momo_network),
            ];
        } elseif ($request->payment_method === 'bank') {
            $validator = Validator::make($request->all(), [
                'bank_reference' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $paymentDetails = [
                'reference' => $request->bank_reference,
                'bank_name' => 'GCB Bank Ghana',
                'account_name' => 'Apex Football League',
                'account_number' => '1234567890',
            ];
        } elseif ($request->payment_method === 'card') {
            $paymentDetails = [
                'provider' => 'paystack',
                'payer_email' => $request->input('payer_email'),
            ];
        }

        return $paymentDetails;
    }

    private function processByMethod(Payment $payment, Request $request, ?string $payerEmail = null)
    {
        switch ($payment->payment_method) {
            case 'momo':
                return $this->processMobileMoney($payment, $request);
            case 'bank':
                return $this->processBankTransfer($payment, $request);
            case 'card':
                return $this->processCardPayment($payment, $payerEmail);
            default:
                return ['message' => 'Payment initiated successfully'];
        }
    }

    private function processMobileMoney(Payment $payment, Request $request)
    {
        $payment->update([
            'status' => 'pending',
            'transaction_id' => 'MOMO-' . time() . '-' . rand(1000, 9999),
        ]);

        return [
            'message' => 'Mobile money payment initiated. Please complete the transfer using your generated team reference code.',
            'redirect_url' => null,
        ];
    }

    private function processBankTransfer(Payment $payment, Request $request)
    {
        $payment->update([
            'status' => 'pending',
            'transaction_id' => $request->bank_reference ?? 'BANK-' . time(),
        ]);

        return [
            'message' => 'Bank transfer details recorded. Your registration will be verified after payment confirmation.',
            'redirect_url' => null,
        ];
    }

    private function processCardPayment(Payment $payment, ?string $payerEmail)
    {
        $secretKey = config('services.paystack.secret_key');
        $paymentUrl = rtrim(config('services.paystack.payment_url', 'https://api.paystack.co'), '/');
        $callbackUrl = config('services.paystack.callback_url') ?: route('payment.callback');

        if (empty($secretKey)) {
            throw new \RuntimeException('PAYSTACK_SECRET_KEY is not configured.');
        }

        if (empty($payerEmail)) {
            throw new \RuntimeException('No payer email was provided for this Paystack transaction.');
        }

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->post($paymentUrl . '/transaction/initialize', [
                'amount' => (int) round(((float) $payment->amount) * 100),
                'email' => $payerEmail,
                'reference' => $payment->reference_code,
                'callback_url' => $callbackUrl,
                'metadata' => [
                    'team_id' => $payment->team_id,
                    'payment_id' => $payment->id,
                    'team_reference_code' => optional($payment->team)->reference_code,
                    'payment_reference_code' => $payment->reference_code,
                ],
            ]);

        if (!$response->successful() || !$response->json('status')) {
            $message = $response->json('message') ?: 'Unable to initialize Paystack payment.';
            $payment->markAsFailed($message, [
                'provider' => 'paystack',
                'initialize_response' => $response->json(),
            ]);

            throw new \RuntimeException($message);
        }

        $data = $response->json('data', []);

        $payment->update([
            'status' => 'processing',
            'payment_details' => array_merge($payment->payment_details ?? [], [
                'provider' => 'paystack',
                'payer_email' => $payerEmail,
                'authorization_url' => $data['authorization_url'] ?? null,
                'access_code' => $data['access_code'] ?? null,
            ]),
        ]);

        return [
            'message' => 'Redirecting to Paystack...',
            'redirect_url' => $data['authorization_url'] ?? null,
        ];
    }

    private function getInitialPaymentStatus($method)
    {
        return $method === 'card' ? 'processing' : 'pending';
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference') ?: $request->query('trxref');

        if (!$reference) {
            return redirect()->route('team.register.form', [
                'payment' => 'failed',
                'message' => 'Missing payment reference from Paystack callback.',
            ]);
        }

        $payment = Payment::where('reference_code', $reference)
            ->with('team')
            ->first();

        if (!$payment) {
            return redirect()->route('team.register.form', [
                'payment' => 'failed',
                'message' => 'Payment record not found.',
            ]);
        }

        $verification = $this->verifyWithPaystack($reference);

        if (!$verification['success']) {
            $payment->markAsFailed($verification['message']);

            return redirect()->route('team.register.form', [
                'payment' => 'failed',
                'reference_code' => optional($payment->team)->reference_code,
                'payment_reference' => $payment->reference_code,
                'message' => $verification['message'],
            ]);
        }

        $data = $verification['data'];

        $payment->update([
            'transaction_id' => (string) ($data['id'] ?? $payment->transaction_id),
            'payment_details' => array_merge($payment->payment_details ?? [], [
                'provider' => 'paystack',
                'gateway_response' => $data['gateway_response'] ?? null,
                'channel' => $data['channel'] ?? null,
                'paid_at_gateway' => $data['paid_at'] ?? null,
            ]),
        ]);

        $payment->markAsVerified();

        return redirect()->route('team.register.form', [
            'payment' => 'success',
            'reference_code' => optional($payment->team)->reference_code,
            'payment_reference' => $payment->reference_code,
        ]);
    }

    public function verifyPayment(Request $request, $referenceCode)
    {
        $payment = Payment::where('reference_code', $referenceCode)
            ->with('team')
            ->firstOrFail();

        if ($payment->payment_method === 'card') {
            $verification = $this->verifyWithPaystack($payment->reference_code);

            if (!$verification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $verification['message'],
                    'status' => $payment->status,
                ], 422);
            }

            $data = $verification['data'];

            $payment->update([
                'transaction_id' => (string) ($data['id'] ?? $payment->transaction_id),
                'payment_details' => array_merge($payment->payment_details ?? [], [
                    'provider' => 'paystack',
                    'gateway_response' => $data['gateway_response'] ?? null,
                    'channel' => $data['channel'] ?? null,
                    'paid_at_gateway' => $data['paid_at'] ?? null,
                ]),
            ]);
        }

        $payment->markAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully',
            'status' => $payment->fresh()->status,
        ]);
    }

    public function checkPaymentStatus($referenceCode)
    {
        $payment = Payment::where('reference_code', $referenceCode)
            ->with('team')
            ->firstOrFail();

        return response()->json([
            'status' => $payment->status,
            'payment_method' => $payment->payment_method,
            'amount' => $payment->amount,
            'paid_at' => $payment->paid_at,
            'verified_at' => $payment->verified_at,
            'team_status' => $payment->team->registration_status,
        ]);
    }

    public function getPaymentDetails($teamId)
    {
        $team = Team::findOrFail($teamId);
        $payment = $team->payment;

        if (!$payment) {
            return response()->json(['error' => 'No payment found for this team'], 404);
        }

        $paymentDetails = $payment->payment_details ?? [];

        return response()->json([
            'payment' => [
                'reference_code' => $payment->reference_code,
                'method' => $payment->payment_method,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'paid_at' => $payment->paid_at,
                'verified_at' => $payment->verified_at,
                'details' => $paymentDetails,
            ],
        ]);
    }

    public function updatePaymentReference(Request $request, $paymentId)
    {
        $validator = Validator::make($request->all(), [
            'transaction_reference' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::findOrFail($paymentId);

        $payment->update([
            'transaction_id' => $request->transaction_reference,
            'notes' => $request->notes ?? $payment->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment reference updated successfully',
        ]);
    }

    public function webhook(Request $request)
    {
        $secret = config('services.paystack.webhook_secret');
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        if (empty($secret) || empty($signature)) {
            return response()->json(['message' => 'Invalid webhook signature.'], 400);
        }

        $expectedSignature = hash_hmac('sha512', $payload, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['message' => 'Webhook signature mismatch.'], 401);
        }

        $event = json_decode($payload, true);

        if (($event['event'] ?? null) === 'charge.success') {
            $reference = $event['data']['reference'] ?? null;

            if ($reference) {
                $payment = Payment::where('reference_code', $reference)
                    ->with('team')
                    ->first();

                if ($payment) {
                    $payment->update([
                        'transaction_id' => (string) ($event['data']['id'] ?? $payment->transaction_id),
                        'payment_details' => array_merge($payment->payment_details ?? [], [
                            'provider' => 'paystack',
                            'gateway_response' => $event['data']['gateway_response'] ?? null,
                            'channel' => $event['data']['channel'] ?? null,
                            'paid_at_gateway' => $event['data']['paid_at'] ?? null,
                        ]),
                    ]);

                    $payment->markAsVerified();
                }
            }
        }

        if (($event['event'] ?? null) === 'charge.failed') {
            $reference = $event['data']['reference'] ?? null;

            if ($reference) {
                $payment = Payment::where('reference_code', $reference)->first();

                if ($payment) {
                    $payment->markAsFailed(
                        $event['data']['gateway_response'] ?? 'Paystack charge failed.',
                        [
                            'provider' => 'paystack',
                            'gateway_response' => $event['data']['gateway_response'] ?? null,
                        ]
                    );
                }
            }
        }

        return response()->json(['status' => 'received']);
    }

    private function verifyWithPaystack(string $reference): array
    {
        $secretKey = config('services.paystack.secret_key');
        $paymentUrl = rtrim(config('services.paystack.payment_url', 'https://api.paystack.co'), '/');

        if (empty($secretKey)) {
            return [
                'success' => false,
                'message' => 'PAYSTACK_SECRET_KEY is not configured.',
            ];
        }

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->get($paymentUrl . '/transaction/verify/' . urlencode($reference));

        if (!$response->successful() || !$response->json('status')) {
            return [
                'success' => false,
                'message' => $response->json('message') ?: 'Unable to verify Paystack transaction.',
            ];
        }

        $data = $response->json('data', []);

        if (($data['status'] ?? null) !== 'success') {
            return [
                'success' => false,
                'message' => $data['gateway_response'] ?? 'Payment has not been completed yet.',
                'data' => $data,
            ];
        }

        return [
            'success' => true,
            'message' => 'Payment verified successfully.',
            'data' => $data,
        ];
    }

    private function getNetworkDisplayName($network)
    {
        return match ($network) {
            'mtn' => 'MTN Mobile Money',
            'voda' => 'Vodafone Cash',
            'airtel' => 'AirtelTigo Money',
            default => $network,
        };
    }

    public function listPayments()
    {
        $payments = Payment::with('team')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($payments);
    }

    public function manualVerify($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->markAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Payment manually verified',
        ]);
    }

    public function refundPayment(Request $request, $paymentId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::findOrFail($paymentId);

        $payment->update([
            'status' => 'refunded',
            'notes' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully',
        ]);
    }
}
