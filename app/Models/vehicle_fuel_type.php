<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle_fuel_type extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_type',
    ];

}
