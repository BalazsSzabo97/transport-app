<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ----------------------------
// PUBLIC ROUTES
// ----------------------------

// Landing page
Route::get('/', function () {
    return view('index'); // resources/views/index.blade.php
})->name('index');

// Login POST handler (for both admin and driver)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Database setup (POST from index page)
Route::post('/setup-database', [SetupController::class, 'setup'])->name('setup.database');


// ----------------------------
// ADMIN ROUTES
// ----------------------------
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Driver Dashboard
    Route::get('/drivers', [AdminController::class, 'listDrivers'])->name('admin.drivers.list');

    // Jobs
    Route::get('/jobs', [AdminController::class, 'listJobs'])->name('admin.jobs.list');
    Route::post('/jobs', [AdminController::class, 'createJob'])->name('admin.jobs.create');
    Route::post('/jobs/{job}/assign', [AdminController::class, 'assignJob'])->name('admin.jobs.assign');
    Route::patch('/jobs/{job}', [AdminController::class, 'updateJob'])->name('admin.jobs.update');
    Route::delete('/jobs/{job}', [AdminController::class, 'deleteJob'])->name('admin.jobs.delete');

});


// ----------------------------
// DRIVER ROUTES
// ----------------------------
Route::prefix('driver')->middleware('auth:driver')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');

    // Jobs
    Route::get('/jobs', [DriverController::class, 'myJobs'])->name('driver.jobs.list');
    Route::patch('/jobs/{job}/status', [DriverController::class, 'updateJobStatus'])->name('driver.jobs.status');

    // Registration
    Route::post('/vehicle', [DriverController::class, 'registerVehicle'])->name('driver.vehicle.register');
});
