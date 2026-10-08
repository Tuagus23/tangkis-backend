<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring.index');

    Route::get('/monitoring/json', [MonitoringController::class, 'json'])
        ->name('monitoring.json');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});