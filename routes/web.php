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

// Register
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

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
    Route::post('/api-token/generate', [AdminController::class, 'generateApiToken'])->name('admin.api-token.generate');

    // Drivers
    Route::get('/drivers', [AdminController::class, 'listDrivers'])->name('admin.drivers.list');
    Route::get('/admin/drivers/pending', [AdminController::class, 'pendingDrivers'])->name('admin.drivers.pending');
    Route::patch('/admin/drivers/{driver}/confirm', [AdminController::class, 'confirmDriver'])->name('admin.drivers.confirm');
    Route::get('/admin/unconfirmed-drivers', [AdminController::class, 'unconfirmedDrivers'])->name('admin.unconfirmed-drivers');

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

Route::middleware(['auth:driver'])->group(function () {
    Route::get('/driver/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
    Route::patch('/driver/jobs/{job}/status', [DriverController::class, 'updateStatus'])->name('driver.jobs.updateStatus');
    Route::post('/driver/vehicle', [DriverController::class, 'registerVehicle'])->name('driver.vehicle.register');
    Route::post('/driver/register', [DriverController::class, 'register'])->name('driver.register.submit');
});