<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle_tran extends Model
{
    use HasFactory;

    protected $fillable = [
        'transmission',
    ];

}
