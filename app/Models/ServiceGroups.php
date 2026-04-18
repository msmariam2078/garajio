<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceGroups extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function serviceMasters()
    {
        return $this->belongsTo(ServiceMaster::class, 'service_master_id');  
    }

    public function vehicle_makes()
    {
        return $this->belongsTo(vehicle_make::class, 'vm_id', 'id');
    }
        public function vehicle_models()
    {
        return $this->belongsTo(vehicle_model::class, 'vmod_id', 'id');
    }
}
