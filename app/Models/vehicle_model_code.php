<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle_model_code extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_code',
    ];

}
