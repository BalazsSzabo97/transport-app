<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'from_address',
        'to_address',
        'recipient_name',
        'recipient_phone',
        'status',
        'driver_id',
    ];

    public $timestamps = false;

    // RELATIONSHIPS

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
