<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// Admin API routes
Route::post('/jobs', [ApiController::class, 'createJob']);
Route::patch('/jobs/{jobId}', [ApiController::class, 'updateJob']);
Route::post('/drivers/{driverId}/confirm', [ApiController::class, 'confirmDriver']);

// Driver API routes
Route::patch('/drivers/{jobId}/status', [ApiController::class, 'updateJobStatus']);