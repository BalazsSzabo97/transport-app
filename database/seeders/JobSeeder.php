<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Driver;

class JobSeeder extends Seeder
{
    public function run()
    {
        $driver1 = Driver::where('email', 'k.janos@mail.com')->first();
        $driver2 = Driver::where('email', 'nagy_sara@mail.com')->first();

        Job::create([
            'from_address' => 'Budapest, Kossuth Lajos utca 1',
            'to_address' => 'Debrecen, Piac utca 10',
            'recipient_name' => 'Elekes István',
            'recipient_phone' => '+36123456789',
            'driver_id' => $driver1->id,
            'status' => 'assigned',
        ]);

        Job::create([
            'from_address' => 'Szeged, Fő tér 5',
            'to_address' => 'Pécs, Rákóczi utca 2',
            'recipient_name' => 'Horváth Anna',
            'recipient_phone' => '+36987654321',
            'driver_id' => $driver2->id,
            'status' => 'assigned',
        ]);
    }
}
