<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'brand',
        'type',
        'plate',
        'driver_id',
    ];

    public $timestamps = false;

    // RELATIONSHIPS

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
