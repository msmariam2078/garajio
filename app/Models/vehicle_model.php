<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function make()
    {
        // return $this->belongsTo(vehicle_make::class, 'make_id', 'id')->withDefault();
        return $this->belongsTo(vehicle_make::class, 'make_id', 'id');
    }
}