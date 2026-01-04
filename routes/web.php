<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes not accessible to superadmin
    Route::middleware(\App\Http\Middleware\PreventSuperadminAccess::class)->group(function () {
        Route::get('/check-in', function () {
            return view('check-in');
        })->name('check-in');
        Route::get('/schedules', function () {
            return view('schedules');
        })->name('schedules');
        Route::get('/reports', function () {
            return view('reports');
        })->name('reports');
    });
    
    // Company management routes (only for superadmin)
    Route::middleware('can:viewAny,App\Models\Company')->group(function () {
        Route::resource('companies', \App\Http\Controllers\CompanyController::class);
        // Admin management for companies
        Route::get('companies/{company}/admins', [\App\Http\Controllers\CompanyController::class, 'admins'])->name('companies.admins');
        Route::get('companies/{company}/admins/create', [\App\Http\Controllers\CompanyController::class, 'createAdmin'])->name('companies.admins.create');
        Route::post('companies/{company}/admins', [\App\Http\Controllers\CompanyController::class, 'storeAdmin'])->name('companies.admins.store');
    });
    
    // Admin management routes (only for superadmin)
    Route::middleware(\App\Http\Middleware\EnsureSuperadmin::class)->group(function () {
        Route::resource('admins', \App\Http\Controllers\AdminController::class);
    });
    
    // Employee management routes (only for admin and supervisor, not superadmin)
    Route::middleware([\App\Http\Middleware\PreventSuperadminAccess::class, 'can:viewAny,App\Models\User'])->group(function () {
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    });
    
    // Leave management routes (for all authenticated users, not superadmin)
    Route::middleware([\App\Http\Middleware\PreventSuperadminAccess::class, 'can:viewAny,App\Models\Leave'])->group(function () {
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class);
        Route::post('leaves/{leave}/approve', [\App\Http\Controllers\LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('leaves/{leave}/reject', [\App\Http\Controllers\LeaveController::class, 'reject'])->name('leaves.reject');
    });
});
