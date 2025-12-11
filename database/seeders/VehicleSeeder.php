<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Driver;

class VehicleSeeder extends Seeder
{
    public function run()
    {

        $driver1 = Driver::where('email', 'k.janos@mail.com')->first();
        $driver2 = Driver::where('email', 'nagy_sara@mail.com')->first();

        Vehicle::create([
            'brand' => 'Ford',
            'type' => 'Truck',
            'plate' => 'ABC123',
            'driver_id' => $driver1->id,
        ]);

        Vehicle::create([
            'brand' => 'Mercedes',
            'type' => 'Van',
            'plate' => 'XYZ789',
            'driver_id' => $driver2->id,
        ]);
    }
}
