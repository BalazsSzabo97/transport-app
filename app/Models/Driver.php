<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;

    // RELATIONSHIPS

    // One driver has one vehicle
    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    // One driver has many jobs
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
