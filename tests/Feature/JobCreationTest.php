<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\AdminSeeder;

class JobCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_job()
    {
        // Disable CSRF middleware for testing
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Seed an admin
        $this->seed(AdminSeeder::class);
        $admin = Admin::first();

        // Act as the admin and create a job
        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.jobs.create'), [
                'from_address' => '123 Start St',
                'to_address' => '456 End Ave',
                'recipient_name' => 'John Doe',
                'recipient_phone' => '1234567890',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('jobs', [
            'from_address' => '123 Start St',
            'to_address' => '456 End Ave',
        ]);
    }
}
