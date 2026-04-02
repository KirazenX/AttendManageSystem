<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;
use App\Http\Controllers\Admin\OfficeLocationController as AdminOfficeLocationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ShiftController as AdminShiftController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\Employee\LeaveController as EmployeeLeaveController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Employee routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/attendance', [EmployeeAttendanceController::class, 'index'])->name('attendance');
        Route::post('/checkin', [EmployeeAttendanceController::class, 'checkIn'])->name('checkin');
        Route::post('/checkout', [EmployeeAttendanceController::class, 'checkOut'])->name('checkout');
        Route::get('/leave', [EmployeeLeaveController::class, 'index'])->name('leave');
        Route::post('/leave', [EmployeeLeaveController::class, 'store'])->name('leave.store');
    });

    // Admin & HR routes
    Route::middleware('role:admin|hr')->prefix('admin')->name('admin.')->group(function () {
        // Attendance
        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/{attendance}', [AdminAttendanceController::class, 'show'])->name('attendance.show');

        // Leave
        Route::get('/leave', [AdminLeaveController::class, 'index'])->name('leave.index');
        Route::get('/leave/{leaveRequest}', [AdminLeaveController::class, 'show'])->name('leave.show');
        Route::put('/leave/{leaveRequest}/approve', [AdminLeaveController::class, 'approve'])->name('leave.approve');
        Route::put('/leave/{leaveRequest}/reject', [AdminLeaveController::class, 'reject'])->name('leave.reject');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');

        // Shifts
        Route::resource('shifts', AdminShiftController::class);

        // Office Locations
        Route::resource('offices', AdminOfficeLocationController::class);

        // Users
        Route::resource('users', AdminUserController::class);
    });
});


