<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{

    public function myJobs()
    {
        $driver = Auth::guard('driver')->user();
        $jobs = $driver->jobs()->get();

        return response()->json($jobs);
    }

    public function updateJobStatus(Request $request, $jobId)
    {
        $driver = Auth::guard('driver')->user();

        $request->validate([
            'status' => 'required|in:in_progress,completed,failed',
        ]);

        $job = Job::where('id', $jobId)->where('driver_id', $driver->id)->firstOrFail();
        $job->status = $request->status;
        $job->save();

        return response()->json($job);
    }
}
