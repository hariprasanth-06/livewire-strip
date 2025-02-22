<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/form', function () {
    return view('test.form');
});

Route::get('/checkout', [FormController::class, 'checkout'])->name('payment.checkout');
Route::get('/success', [FormController::class, 'success'])->name('payment.success');
Route::get('/cancel', [FormController::class, 'cancel'])->name('payment.cancel');
Route::get('/cancel', [FormController::class, 'cancel'])->name('payment.cancel');
