<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Routes d'inscription
    Route::get('/register/choose', [RegisterController::class, 'showProfileChoice'])
        ->name('register.choose');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register']);
});
