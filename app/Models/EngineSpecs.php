<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineSpecs extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function make()
    {
        return $this->belongsTo(vehicle_make::class, 'make_id');
    }
    
    public function model()
    {
        return $this->belongsTo(vehicle_model::class, 'model_id');
    }
}