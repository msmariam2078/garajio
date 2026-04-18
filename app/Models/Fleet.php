<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'name',
        'type',
        'make',
        'model',
        'warehouse',
        'technician',
        'supervisor',
        'service',
        'status',
    ];

    
}

