<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeamRegistrationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Registration routes
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/team/registration', [TeamRegistrationController::class, 'showForm'])->name('home');
Route::get('/register', [TeamRegistrationController::class, 'showForm'])->name('team.register.form');
Route::post('/api/team-info', [TeamRegistrationController::class, 'storeTeamInfo'])->name('api.team.info');
Route::post('/api/owners', [TeamRegistrationController::class, 'storeOwners'])->name('api.team.owners');

// Payment routes
Route::post('/api/process-payment', [PaymentController::class, 'processPayment'])->name('api.payment.process');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/api/payment/status/{referenceCode}', [PaymentController::class, 'checkPaymentStatus'])->name('api.payment.status');
Route::post('/api/payment/verify/{referenceCode}', [PaymentController::class, 'verifyPayment'])->name('api.payment.verify');
Route::get('/api/payment/details/{teamId}', [PaymentController::class, 'getPaymentDetails'])->name('api.payment.details');
Route::post('/api/payment/update-reference/{paymentId}', [PaymentController::class, 'updatePaymentReference'])->name('api.payment.update-reference');
Route::post('/api/payment/webhook', [PaymentController::class, 'webhook'])->name('api.payment.webhook');

// Team status routes
Route::get('/api/team-status/{referenceCode}', [TeamRegistrationController::class, 'getTeamStatus'])->name('api.team.status');

// Admin routes (protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/payments', [PaymentController::class, 'listPayments'])->name('admin.payments');
    Route::post('/payment/{paymentId}/verify', [PaymentController::class, 'manualVerify'])->name('admin.payment.verify');
    Route::post('/payment/{paymentId}/refund', [PaymentController::class, 'refundPayment'])->name('admin.payment.refund');
});
