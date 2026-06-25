<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'team_id',
        'reference_code',
        'payment_method',
        'amount',
        'status',
        'payment_details',
        'transaction_id',
        'paid_at',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public static function generateReferenceCode()
    {
        do {
            $reference = 'PAY-' . now()->format('YmdHis') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (self::where('reference_code', $reference)->exists());

        return $reference;
    }

    public function markAsCompleted(?string $transactionId = null, array $paymentDetails = []): void
    {
        $details = $this->payment_details ?? [];

        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'payment_details' => array_merge($details, $paymentDetails),
            'paid_at' => now(),
        ]);
    }

    public function markAsVerified(): void
    {
        $this->update([
            'status' => 'completed',
            'verified_at' => now(),
            'paid_at' => $this->paid_at ?? now(),
        ]);

        if ($this->team) {
            $this->team->update([
                'registration_status' => 'approved',
            ]);
        }
    }

    public function markAsFailed(?string $notes = null, array $paymentDetails = []): void
    {
        $details = $this->payment_details ?? [];

        $this->update([
            'status' => 'failed',
            'notes' => $notes ?? $this->notes,
            'payment_details' => array_merge($details, $paymentDetails),
        ]);

        if ($this->team) {
            $this->team->update([
                'registration_status' => 'submitted',
            ]);
        }
    }
}
