<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment_item extends Model
{
    use HasFactory;
    //public $timestamps = false;
    protected $fillable=[
        
        'service_part_id',
        'quantity',
       'unit_price',
        'location',
        'expir_day'
    ];

    public function servicePart()
    {
        return $this->belongsTo('App\Models\ServicePart','service_part_id');
    }
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarHouse','location');
    }
}
