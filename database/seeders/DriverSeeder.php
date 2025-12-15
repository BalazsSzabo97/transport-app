<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run()
    {
        Driver::create([
            'name' => 'Kovács János',
            'email' => 'k.janos@mail.com',
            'password' => Hash::make('driverpass'),
            'is_confirmed' => true,
            'token' => '9xLPW4CPz5VrH7FFAgnCMXVZtEgghnl7UqBplZI3'
        ]);

        Driver::create([
            'name' => 'Nagy Sára',
            'email' => 'nagy_sara@mail.com',
            'password' => Hash::make('driverpass'),
            'is_confirmed' => true,
            'token' => 'YSsOdxa5RvmLvUw6mLmDPPSeIrm08TqZV3Y6Y6jF'
        ]);
    }
}
