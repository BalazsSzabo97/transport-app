<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function updateStatus(Request $request, Job $job)
    {
        $driver = Auth::guard('driver')->user();

        if ($job->driver_id !== $driver->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,failed',
        ]);

        $job->status = $request->status;
        $job->save();

        return redirect()
            ->route('driver.dashboard')
            ->with('success', 'Munka státusza frissítve.');
    }

    public function registerVehicle(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        if ($driver->vehicle) {
            return redirect()
                ->route('driver.dashboard')
                ->with('error', 'Már regisztrált járműve van.');
        }

        $request->validate([
            'brand' => 'required|string',
            'type' => 'required|string',
            'plate' => 'required|string|unique:vehicles,plate',
        ]);

        $vehicle = $driver->vehicle()->create([
            'brand' => $request->brand,
            'type' => $request->type,
            'plate' => $request->plate,
        ]);

        return redirect()
            ->route('driver.dashboard')
            ->with('success', 'Jármű regisztrálva.');
    }

    public function dashboard()
    {
        $driver = Auth::guard('driver')->user();

        $jobs = Job::where('driver_id', $driver->id)
            ->orderBy('id', 'desc')
            ->get();

        $statusLabels = [
            'assigned' => 'Kiosztva',
            'in_progress' => 'Folyamatban',
            'completed' => 'Elvégezve',
            'failed' => 'Sikertelen',
        ];

        return view('driver.dashboard', compact('driver', 'jobs', 'statusLabels'));

    }
}
