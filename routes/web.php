<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeamRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\ArticleController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Interaction
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
Route::post('/predictions', [PredictionController::class, 'store'])->name('predictions.store');

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/teams/{id}', [TeamController::class, 'showPublic'])->name('teams.show');
Route::get('/players/{id}', [PlayerController::class, 'showPublic'])->name('players.show');

// Registration routes
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
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/payments', [PaymentController::class, 'listPayments'])->name('admin.payments');
    Route::post('/payment/{paymentId}/verify', [PaymentController::class, 'manualVerify'])->name('admin.payment.verify');
    Route::post('/payment/{paymentId}/refund', [PaymentController::class, 'refundPayment'])->name('admin.payment.refund');

    // Game Management
    Route::resource('games', GameController::class)->names('admin.games');

    // News Management
    Route::resource('articles', ArticleController::class)->names('admin.articles');
});

// Manager routes (protected)
Route::middleware(['auth', 'manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

    Route::post('/players', [PlayerController::class, 'store'])->name('manager.players.store');
    Route::put('/players/{id}', [PlayerController::class, 'update'])->name('manager.players.update');
    Route::delete('/players/{id}', [PlayerController::class, 'destroy'])->name('manager.players.destroy');
});
