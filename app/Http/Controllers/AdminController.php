<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function listDrivers()
    {
        $drivers = Driver::with('vehicle', 'jobs')->get();
        return response()->json($drivers);
    }

    public function createJob(Request $request)
    {
        $request->validate([
            'from_address' => 'required|string',
            'to_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
        ]);

        $job = Job::create([
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'driver_id' => null,
            'status' => 'assigned',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Új munka létrehozva!');
    }

    public function assignJob(Request $request, $jobId)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $driver = Driver::where('id', $request->driver_id)
            ->where('is_confirmed', true)
            ->firstOrFail();

        $job = Job::findOrFail($jobId);
        $job->driver_id = $driver->id;
        $job->status = 'assigned';
        $job->save();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'A munka sikeresen kiosztva!');
    }

    public function updateJob(Request $request, $jobId)
    {
        $request->validate([
            'from_address' => 'required|string',
            'to_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
        ]);

        $job = Job::findOrFail($jobId);
        $job->from_address = $request->from_address;
        $job->to_address = $request->to_address;
        $job->recipient_name = $request->recipient_name;
        $job->recipient_phone = $request->recipient_phone;
        $job->save();

        return redirect()->route('admin.dashboard')->with('success', 'Munka frissítve!');
    }


    public function deleteJob($jobId)
    {
        $job = Job::findOrFail($jobId);
        $job->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Munka törölve!');
    }

    public function unconfirmedDrivers()
    {
        $drivers = Driver::where('is_confirmed', false)->get();
        return view('admin.unconfirmed_drivers', compact('drivers'));
    }

    public function confirmDriver($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        $driver->is_confirmed = true;
        $driver->save();

        return redirect()->back()->with('success', 'A fuvarozó sikeresen jóváhagyva.');
    }

    public function generateApiToken()
    {
        $admin = Auth::guard('admin')->user();

        $admin->token = Str::random(40);
        $admin->save();

        return redirect()
            ->back()
            ->with('success', 'API token sikeresen létrehozva.');
    }

    public function dashboard(Request $request)
    {
        $query = Job::with('driver')->orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->get();
        $drivers = Driver::orderBy('name')->get();
        $confirmedDrivers = Driver::where('is_confirmed', true)->orderBy('name')->get();

        $statusLabels = [
            'assigned' => 'Kiosztva',
            'in_progress' => 'Folyamatban',
            'completed' => 'Elvégezve',
            'failed' => 'Sikertelen'
        ];

        return view('admin.dashboard', compact('jobs', 'drivers', 'confirmedDrivers', 'statusLabels'));
    }


}
