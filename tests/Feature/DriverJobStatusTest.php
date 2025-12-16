<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Job;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DriverJobStatusTest extends TestCase
{

    use RefreshDatabase;

    public function test_driver_can_update_own_job_status()
    {
        $this->seed(\Database\Seeders\DriverSeeder::class);
        $driver = Driver::first();

        $job = Job::create([
            'from_address' => 'A',
            'to_address' => 'B',
            'recipient_name' => 'Test',
            'recipient_phone' => '123',
            'driver_id' => $driver->id,
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($driver, 'driver')
            ->patch("/driver/jobs/{$job->id}/status", [
                'status' => 'in_progress',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'in_progress',
        ]);
    }
}