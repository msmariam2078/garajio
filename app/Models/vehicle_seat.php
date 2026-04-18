<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle_seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seating_capacity',
    ];

}
