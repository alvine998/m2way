<?php

use App\Http\Controllers\AccountingCategoryController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\JamaahGroupController;
use App\Http\Controllers\PackageCategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\PlanScheduleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGuideController;
use App\Http\Controllers\TravelDocumentController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/planning/timeline', [PlanningController::class, 'timeline'])->name('planning.timeline');
    Route::get('/planning/calendar', [PlanningController::class, 'calendar'])->name('planning.calendar');
    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{customer}/attachments', [\App\Http\Controllers\CustomerAttachmentController::class, 'store'])->name('customer-attachments.store');
    Route::delete('/customers/{customer}/attachments/{attachment}', [\App\Http\Controllers\CustomerAttachmentController::class, 'destroy'])->name('customer-attachments.destroy');
    Route::get('/customers/{customer}/attachments/{attachment}/download', [\App\Http\Controllers\CustomerAttachmentController::class, 'download'])->name('customer-attachments.download');
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/export/pdf', [FinanceController::class, 'exportPdf'])->name('finance.export.pdf');
    Route::get('/finance/export/excel', [FinanceController::class, 'exportExcel'])->name('finance.export.excel');
    Route::resource('accounting', AccountingController::class)->except(['show']);
    Route::resource('accounting-categories', AccountingCategoryController::class)->except(['show']);
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/user-guide', [UserGuideController::class, 'index'])->name('user-guide.index');
    Route::resource('packages', PackageController::class)->except(['show']);
    Route::resource('package-categories', PackageCategoryController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['show']);
    Route::put('/transactions/{transaction}/refund', [TransactionController::class, 'refund'])->name('transactions.refund');
    Route::post('/transactions/{transaction}/payments', [TransactionController::class, 'storePayment'])->name('transactions.payments.store');
    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
    Route::post('/transactions/invoices/bulk', [TransactionController::class, 'invoicesBulk'])->name('transactions.invoices.bulk');
    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
    Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export.pdf');
    Route::resource('jamaah-groups', JamaahGroupController::class);
    Route::resource('travel-documents', TravelDocumentController::class);
    Route::resource('plan-schedules', PlanScheduleController::class)->except(['show']);
    Route::get('/travel-documents/{travelDocument}/download', [TravelDocumentController::class, 'download'])->name('travel-documents.download');

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show']);
    });
});
