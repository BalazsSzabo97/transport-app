<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class SetupController extends Controller
{
    public function setup()
    {
        // Run migrations & seeders
        Artisan::call('migrate:fresh --seed');

        return redirect()->back()->with('status', 'Adatbázis sikeresen létrehozva és feltöltve!');
    }
}
