<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\DetectionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:60,1')->group(function (): void {
    Route::get('/health', static fn () => response()->json([
        'status' => 'ok',
        'service' => 'tangkis-api',
        'version' => 'v1',
        'time' => now()->toIso8601String(),
    ]));

    Route::get('/detections', [DetectionController::class, 'index']);
    Route::post('/detections', [DetectionController::class, 'store']);
    Route::get('/stats', [DetectionController::class, 'stats']);
});
