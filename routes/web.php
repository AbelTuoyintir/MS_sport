<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeamRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TeamActivityController;
use App\Http\Controllers\TeamOperationsController;
use App\Http\Controllers\ReportController;
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
Route::post('/teams/{id}/update', [TeamController::class, 'update'])->name('teams.update');
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
    Route::get('/games/{game}/events', [GameController::class, 'events'])->name('admin.games.events');
    Route::post('/games/{game}/events', [GameController::class, 'storeEvent'])->name('admin.games.events.store');
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

    // Staff Management
    Route::post('/staff', [StaffController::class, 'store'])->name('manager.staff.store');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('manager.staff.destroy');

    // Transfer Market
    Route::get('/transfers', [TransferController::class, 'index'])->name('manager.transfers.index');
    Route::post('/transfers/list', [TransferController::class, 'listPlayer'])->name('manager.transfers.list');
    Route::post('/transfers/offer', [TransferController::class, 'makeOffer'])->name('manager.transfers.offer');
    Route::post('/transfers/handle/{id}', [TransferController::class, 'handleOffer'])->name('manager.transfers.handle');

    // Training & Injuries
    Route::get('/training', [TeamActivityController::class, 'trainingIndex'])->name('manager.training.index');
    Route::post('/training', [TeamActivityController::class, 'storeTraining'])->name('manager.training.store');
    Route::get('/injuries', [TeamActivityController::class, 'injuryIndex'])->name('manager.injuries.index');
    Route::post('/injuries', [TeamActivityController::class, 'storeInjury'])->name('manager.injuries.store');

    // Operations
    Route::get('/finance', [TeamOperationsController::class, 'financeIndex'])->name('manager.finance.index');
    Route::post('/finance', [TeamOperationsController::class, 'storeFinance'])->name('manager.finance.store');
    Route::get('/equipment', [TeamOperationsController::class, 'equipmentIndex'])->name('manager.equipment.index');
    Route::post('/equipment', [TeamOperationsController::class, 'storeEquipment'])->name('manager.equipment.store');
    Route::get('/scouting', [TeamOperationsController::class, 'scoutingIndex'])->name('manager.scouting.index');
    Route::post('/scouting', [TeamOperationsController::class, 'storeScout'])->name('manager.scouting.store');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('manager.reports.index');
    Route::get('/reports/export/players', [ReportController::class, 'exportPlayers'])->name('manager.reports.export.players');
});
