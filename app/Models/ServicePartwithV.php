<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePartwithV extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function servicePart()
    {
        return $this->belongsTo(ServicePart::class, 'service_part_id', 'id');
    }

  
    public function vehicleMake()
    {
        return $this->belongsTo(vehicle_make::class, 'v_make', 'id');
    }

   
    public function vehicleModel()
    {
        return $this->belongsTo(vehicle_model::class, 'v_model', 'id');
    }
}
