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
        ]);

        Driver::create([
            'name' => 'Nagy Sára',
            'email' => 'nagy_sara@mail.com',
            'password' => Hash::make('driverpass'),
        ]);
    }
}
