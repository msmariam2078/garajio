<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    use HasFactory;
    //public $timestamps = false;
    protected $fillable=[
        
        'service_part_id',
        'quantity',
        'reference',
        'document',
       'invoice_id',
        'location',
        'expir_day'
    ];

    public function servicePart()
    {
        return $this->belongsTo('App\Models\ServicePart','service_part_id');
    }
    
}

