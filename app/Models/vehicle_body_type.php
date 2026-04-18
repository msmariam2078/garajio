<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle_body_type extends Model
{
    use HasFactory;

    protected $fillable = [
        'body_type',
    ];

}
