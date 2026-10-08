<?php

use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/monitoring', [MonitoringController::class, 'index'])
    ->name('monitoring.index');
