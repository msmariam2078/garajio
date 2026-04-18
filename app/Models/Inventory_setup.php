<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory_setup extends Model
{
    use HasFactory;
    protected $fillable=[
        
        'auto',
        'item_prefix',
        'item_number',
        'hand_availability',
    
    ];
}
