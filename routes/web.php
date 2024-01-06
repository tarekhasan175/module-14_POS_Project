<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;




// Web Api Routes for User
Route::post('/user-registration', [UserController::class, 'userRegistration']);
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/send-otp', [UserController::class, 'sendOTP']);
Route::post('/verify-otp', [UserController::class, 'verifyOTP']);
Route::post('/set-password', [UserController::class, 'setPassword'])->middleware([TokenVerificationMiddleware::class]);


//User Logout Route
Route::get('/user-logout', [UserController::class, 'logout']);
// Web pages Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/user-login', [UserController::class, 'userLoginPage'])->name('user-login');
Route::get('/user-registration', [UserController::class, 'userRegistrationPage'])->name('user-registration');
Route::get('/send-otp', [UserController::class, 'sendOTPPage'])->name('send-otp');
Route::get('/verify-otp', [UserController::class, 'verifyOTPPage'])->name('verify-otp');
Route::get('/set-password', [UserController::class, 'setPasswordPage'])->name('set-password')->middleware([TokenVerificationMiddleware::class]);
Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware([TokenVerificationMiddleware::class]);

