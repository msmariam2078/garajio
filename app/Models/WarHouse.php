<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarHouse extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
    return $this->belongsTo(User::class, 'technicians');
    }
 public function items()
    {
    return $this->hasMany(ServicePartadjustMent::class, 'warehouse_id');
    }

}