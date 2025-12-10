<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Clients\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Loans\LoanController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Settings\BusinessSettingController;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/archive', [ClientController::class, 'archive'])->name('clients.archive');
    Route::post('clients/{client}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('clients/{client}/documents/{document}', [ClientController::class, 'destroyDocument'])->name('clients.documents.destroy');
    Route::resource('loans', LoanController::class);
    Route::resource('payments', PaymentController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/clients', [ReportController::class, 'exportClients'])->name('clients');
        Route::get('/loans', [ReportController::class, 'exportLoans'])->name('loans');
        Route::get('/payments', [ReportController::class, 'exportPayments'])->name('payments');
        Route::get('/pdf/portfolio', [ReportController::class, 'pdfPortfolio'])->name('pdf.portfolio');
    });

    Route::get('settings/business', [BusinessSettingController::class, 'edit'])->name('settings.business');
    Route::put('settings/business', [BusinessSettingController::class, 'update'])->name('settings.business.update');

    Route::get('/search', SearchController::class)->name('search');
});
