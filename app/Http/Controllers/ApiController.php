<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Job;
use App\Models\Driver;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private function checkToken(Request $request)
    {
        $token = $request->header('token');

        if (!$token) {
            return response()->json(['error' => 'API token missing'], 401);
        }

        $admin = Admin::where('token', $token)->first();

        if (!$admin) {
            return response()->json(['error' => 'Invalid API token'], 403);
        }

        return $admin;
    }

    private function checkDriverToken(Request $request)
    {
        $token = $request->header('token');
        if (!$token) {
            return response()->json(['error' => 'API token missing'], 401);
        }

        $driver = Driver::where('token', $token)->first();
        if (!$driver) {
            return response()->json(['error' => 'Invalid API token'], 403);
        }

        return $driver;
    }


    public function createJob(Request $request)
    {
        $auth = $this->checkToken($request);
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        $data = $request->validate([
            'from_address' => 'required|string',
            'to_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
        ]);

        $job = Job::create([
            ...$data,
            'driver_id' => null,
            'status' => 'assigned',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job created successfully',
            'job' => $job,
        ], 201);
    }

    public function updateJob(Request $request, $jobId)
    {
        $admin = $this->checkToken($request);
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        $job = Job::findOrFail($jobId);

        $fields = ['from_address', 'to_address', 'recipient_name', 'recipient_phone', 'status'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $job->$field = $request->$field;
            }
        }

        $job->save();

        return response()->json([
            'success' => true,
            'message' => 'Job updated successfully',
            'job' => $job
        ]);
    }


    public function confirmDriver(Request $request, $driverId)
    {
        $auth = $this->checkToken($request);
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        $driver = Driver::findOrFail($driverId);
        $driver->update(['is_confirmed' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Driver confirmed successfully',
            'driver' => $driver,
        ]);
    }

    public function updateJobStatus(Request $request, $jobId)
{
    $driver = $this->checkDriverToken($request);
    if ($driver instanceof \Illuminate\Http\JsonResponse) {
        return $driver;
    }

    $request->validate([
        'status' => 'required|in:assigned,in_progress,completed,failed'
    ]);

    $job = Job::findOrFail($jobId);

    if ($job->driver_id !== $driver->id) {
        return response()->json(['error' => 'You are not assigned to this job'], 403);
    }

    $job->status = $request->status;
    $job->save();

    return response()->json([
        'success' => true,
        'message' => 'Job status updated successfully',
        'job' => $job
    ]);
}

}
