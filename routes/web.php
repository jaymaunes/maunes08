<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MeterReadingController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WaterRateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User profile routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Admin only routes
    Route::middleware('admin')->group(function () {
        // User management routes
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.make-admin');
    });

    // Customer management routes
    Route::resource('customers', CustomerController::class);

    // Meter reading routes
    Route::resource('meter-readings', MeterReadingController::class);

    // Bill routes
    Route::resource('bills', BillController::class);

    // Payment routes
    Route::resource('payments', PaymentController::class);

    // Water rate routes
    Route::resource('water-rates', WaterRateController::class);

    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
