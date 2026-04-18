<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMaster extends Model
{
    use HasFactory;
    public function units()
    {
        return $this->belongsTo(unit::class, 'unitId');
    }

  
    public function skillGroups()
    {
        return $this->belongsTo(SkillGroup::class, 'skillId');
    }

    
}