<?php

use App\Http\Controllers\Auth\QrLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Redirect / to /login or /dashboard
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [QrLoginController::class, 'showForm'])->name('login');
Route::post('/login/verify', [QrLoginController::class, 'verify'])->name('login.verify');
Route::post('/logout', [QrLoginController::class, 'logout'])->name('logout');

// User Dashboard & Report Flow
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/quick-scan', [DashboardController::class, 'quickScan'])->name('dashboard.quick-scan');
    Route::get('/dashboard/devices/manage', [DashboardController::class, 'manage'])->name('dashboard.devices.manage');
    Route::post('/dashboard/devices/assign', [DashboardController::class, 'assign'])->name('dashboard.devices.assign');
    Route::post('/dashboard/devices/assign-from-room', [DashboardController::class, 'assignFromRoom'])->name('dashboard.devices.assign-from-room');
    Route::post('/dashboard/devices/assign-to-room', [DashboardController::class, 'assignToRoom'])->name('dashboard.devices.assign-to-room');
    Route::post('/dashboard/devices/unassign', [DashboardController::class, 'unassign'])->name('dashboard.devices.unassign');
    Route::post('/dashboard/devices/swap', [DashboardController::class, 'swap'])->name('dashboard.devices.swap');
    Route::post('/dashboard/devices/move-to-gudang', [DashboardController::class, 'moveToGudang'])->name('dashboard.devices.move-to-gudang');
    Route::get('/dashboard/devices/{id}', [DashboardController::class, 'show'])->name('dashboard.devices.show');
    
    Route::get('/report/open-ticket', [ReportController::class, 'openTicket'])->name('report.open-ticket');
    Route::get('/report/create/{device_id}', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/report/status/{device_id}', [ReportController::class, 'status'])->name('report.status');
    
    // User Report History
    Route::get('/reports/history', [ReportController::class, 'history'])->name('reports.history');
    Route::patch('/reports/history/{ticketId}/rating', [ReportController::class, 'rate'])->name('reports.history.rate');
    Route::get('/reports/history/{id}', [ReportController::class, 'showReport'])->name('reports.history.show');
});

// Admin / Jarkom Flow
Route::middleware(['auth', 'jarkom'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/{id}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{id}', [AdminReportController::class, 'update'])->name('reports.update');
    
    // CRUD Management resources
    Route::resource('/users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('/devices', \App\Http\Controllers\Admin\DeviceController::class);
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
});
