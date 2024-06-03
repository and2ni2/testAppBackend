<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestCategoryController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RequestMessagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('get-user', [AuthController::class, 'getUser'])->name('get-user');
    Route::prefix('request')->name('request.')->group(function () {
        Route::get('list', [RequestController::class, 'list'])->middleware(['permission:view requests'])->name('send');
        Route::post('create', [RequestController::class, 'create'])->middleware(['permission:create requests'])->name('create');
        Route::get('show/{id}', [RequestController::class, 'show'])->middleware(['permission:view requests'])->name('close');
        Route::put('close/{id}', [RequestController::class, 'close'])->middleware(['permission:close requests'])->name('close');

        Route::prefix('category')->name('category.')->group(function () {
            Route::get('list', [RequestCategoryController::class, 'list'])->middleware(['permission:view categories'])->name('list');
        });

        Route::prefix('message')->name('message.')->group(function () {
            Route::post('send', [RequestMessagesController::class, 'store'])->middleware(['permission:add messages'])->name('send');
        });
    });
});
