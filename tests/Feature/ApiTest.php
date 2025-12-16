<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_job_api()
    {
        $this->seed(\Database\Seeders\AdminSeeder::class);
        $admin = Admin::first();
        $admin->token = bin2hex(random_bytes(40)); // Admin in db doesn't yet have a token
        $admin->save();

        $response = $this->withHeaders([
            'token' => $admin->token
        ])->postJson('/api/jobs', [
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
            'recipient_name' => 'John Doe',
            'recipient_phone' => '1234567890',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'job' => [
                         'from_address' => '123 Start St',
                         'to_address' => '456 End Ave',
                     ],
                 ]);

        $this->assertDatabaseHas('jobs', [
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
        ]);
    }

    public function test_driver_can_update_own_job_status_api()
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

        $response = $this->withHeaders([
            'token' => $driver->token,
        ])->patchJson("/api/drivers/{$job->id}/status", [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'job' => [
                         'status' => 'in_progress',
                     ],
                 ]);

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_driver_cannot_update_other_driver_job_api()
    {
        $this->seed(\Database\Seeders\DriverSeeder::class);
        $drivers = Driver::all();
        $driver = $drivers->first();
        $otherDriver = $drivers->last();

        $job = Job::create([
            'from_address' => 'A',
            'to_address' => 'B',
            'recipient_name' => 'Test',
            'recipient_phone' => '123',
            'driver_id' => $otherDriver->id,
            'status' => 'assigned',
        ]);

        $response = $this->withHeaders([
            'token' => $driver->token,
        ])->patchJson("/api/drivers/{$job->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'assigned',
        ]);
    }
}