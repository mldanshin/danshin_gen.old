<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'handleForm'])
                ->middleware('guest')
                ->name('register.handler');

Route::get('/register/confirmation/{userId}', [RegisteredUserController::class, 'createConfirmation'])
                ->middleware('guest')
                ->name('register.confirmation');

Route::post('/register/confirmation', [RegisteredUserController::class, 'confirm'])
                ->middleware('guest')
                ->name('register.confirmation.handler');

Route::get('/register/confirmation-repeated/{userId}', [RegisteredUserController::class, 'createRepeatConfirmation'])
                ->middleware('guest')
                ->name('register.confirmation-repeated');

Route::post('/register/confirmation-repeated', [RegisteredUserController::class, 'repeatConfirmation'])
                ->middleware('guest')
                ->name('register.confirmation-repeated.handler');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login.handler');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
