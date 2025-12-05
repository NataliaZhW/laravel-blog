<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    //    REGISTER
    Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    //    LOGIN
    Route::get('login', [AuthController::class, 'loginView'])->name('login.view');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::get('search', [SearchController::class, 'search'])->name('search');
Route::get('search-ajax', [SearchController::class, 'searchAjax'])->name('search.ajax');