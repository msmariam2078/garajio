<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillGroup extends Model
{
    use HasFactory;

    public function skill()
    {
        return $this->belongsTo(SkillList::class, 'skill_id');  
    }
    
public function vehicleMake()
{
    return $this->belongsTo(vehicle_make::class, 'vehicle_make_id');
}

public function vehicleModel()
{
    return $this->belongsTo(vehicle_model::class, 'vehicle_model_id');
}
}
