<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    
   protected $fillable = [
        'service_part_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'description',
        'unit',
        'qty_transfer',
        'stock'

    ];
    
    public function servicePart()
    {
        return $this->belongsTo('App\Models\ServicePart','service_part_id');
    }
    public function fromwarehouse()
    {
        return $this->belongsTo('App\Models\WarHouse','from_warehouse_id');
    }
       public function towarehouse()
    {
        return $this->belongsTo('App\Models\WarHouse','to_warehouse_id');
    }
}
