<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@adminmail.com',
            'password' => Hash::make('password123'),
            'token' => null,
        ]);
    }
}
