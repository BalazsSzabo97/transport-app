<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_job()
    {
        $this->seed(\Database\Seeders\AdminSeeder::class);
        $admin = Admin::first();

        $job = Job::create([
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
            'recipient_name' => 'John Doe',
            'recipient_phone' => '1234567890',
            'driver_id' => null,
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.jobs.update', ['job' => $job->id]), [
                'from_address' => '789 New St',
                'to_address' => '101 New Ave',
                'recipient_name' => 'Jane Doe',
                'recipient_phone' => '0987654321',
                'status' => 'in_progress',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'from_address' => '789 New St',
            'to_address' => '101 New Ave',
            'recipient_name' => 'Jane Doe',
            'recipient_phone' => '0987654321',
            'status' => 'in_progress',
        ]);
    }


    public function test_admin_can_update_partial_job_fields()
    {
        $this->seed(\Database\Seeders\AdminSeeder::class);
        $admin = Admin::first();

        $job = Job::create([
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
            'recipient_name' => 'John Doe',
            'recipient_phone' => '1234567890',
            'driver_id' => null,
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.jobs.update', ['job' => $job->id]), [
                'status' => 'completed',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'completed',
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
            'recipient_name' => 'John Doe',
            'recipient_phone' => '1234567890',
        ]);
    }

    public function test_update_job_invalid_status_fails()
    {
        $this->seed(\Database\Seeders\AdminSeeder::class);
        $admin = Admin::first();

        $job = Job::create([
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
            'recipient_name' => 'John Doe',
            'recipient_phone' => '1234567890',
            'driver_id' => null,
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.jobs.update', ['job' => $job->id]), [
                'from_address' => '123 Start St',
                'to_address' => '456 End Ave',
                'recipient_name' => 'John Doe',
                'recipient_phone' => '1234567890',
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'assigned',
        ]);
    }
}
