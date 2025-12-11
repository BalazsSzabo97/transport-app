<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function listDrivers()
    {
        $drivers = Driver::with('vehicle', 'jobs')->get();
        return response()->json($drivers);
    }

    public function createDriver(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|string|min:6',
        ]);

        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($driver, 201);
    }

    public function assignVehicle(Request $request, $driverId)
    {
        $request->validate([
            'brand' => 'required|string',
            'type' => 'required|string',
            'plate' => 'required|string|unique:vehicles,plate',
        ]);

        $driver = Driver::findOrFail($driverId);

        $vehicle = Vehicle::create([
            'brand' => $request->brand,
            'type' => $request->type,
            'plate' => $request->plate,
            'driver_id' => $driver->id,
        ]);

        return response()->json($vehicle, 201);
    }

    public function createJob(Request $request)
    {
        $request->validate([
            'from_address' => 'required|string',
            'to_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $job = Job::create([
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'driver_id' => $request->driver_id,
            'status' => 'assigned',
        ]);

        return response()->json($job, 201);
    }

    public function assignJob(Request $request, $jobId)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $job = Job::findOrFail($jobId);
        $job->driver_id = $request->driver_id;
        $job->status = 'assigned';
        $job->save();

        return response()->json($job);
    }

    public function updateJobStatus(Request $request, $jobId)
    {
        $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,failed',
        ]);

        $job = Job::findOrFail($jobId);
        $job->status = $request->status;
        $job->save();

        return response()->json($job);
    }
}
