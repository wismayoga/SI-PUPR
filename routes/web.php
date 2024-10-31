<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

Route::middleware(['auth', Auth::class . ':admin'])->group(function () {
    Route::get('/reportsout', [AdminController::class, 'index'])->name('admin.reports');
    Route::get('/reportsout/{id}', [AdminController::class, 'show'])->name('admin.reports.show');
    Route::post('/admin/reports', [AdminController::class, 'store'])->name('admin.reports.store');
    Route::get('/reportsout/download/{id}', [AdminController::class, 'download'])->name('admin.reports.download');
    Route::post('/admin/reports/batch-download', [AdminController::class, 'batchDownload'])->name('admin.reports.batchDownload');
    Route::delete('/reports/{id}', [AdminController::class, 'destroy'])->name('admin.reports.destroy');
    Route::put('/admin/reports/{id}', [AdminController::class, 'update'])->name('admin.reports.update');
    Route::get('/settings/admin', [SettingsController::class, 'adminSettings'])->name('settings.admin');

    Route::post('/settings/admin/reset-password', [SettingsController::class, 'resetAdminPassword'])->name('settings.admin.reset');
    Route::post('/settings/admin/reset-user-password', [SettingsController::class, 'resetUserPassword'])->name('settings.admin.reset-user');
});

// Route::middleware(['Auth', 'role:user'])->group(function () {
Route::middleware(['auth', Auth::class . ':user'])->group(function () {
    Route::get('/reports', [UserController::class, 'index'])->name('user.reports');
    Route::get('/reports/{report}', [UserController::class, 'show'])->name('user.reports.show');
    Route::post('/reports/{report}/submit', [UserController::class, 'submit'])->name('user.reports.submit');
    Route::delete('/submissions/{submission}', [UserController::class, 'destroy'])->name('user.reports.delete');
    Route::get('/settings/user', [SettingsController::class, 'userSettings'])->name('settings.user');
    Route::post('/settings/user/reset-password', [SettingsController::class, 'resetAdminPassword'])->name('settings.user.reset');
});
