<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register_user');
    Route::post('login', [AuthController::class, 'login'])->name('login_user');
});
