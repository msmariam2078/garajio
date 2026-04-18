<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePart extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function serviceTasks()
    {
        return $this->hasMany('App\Models\ServiceTask', 'service_id');
    }
    public function adjustment()
    {
        return $this->hasOne('App\Models\AdjustmentI_item', 'service_part_id');
    }
    public function inventoryDetail()
    {
        return $this->hasOne(InventoryDetail::class, 'service_part_id');
    }

    public function units()
    {
        return $this->belongsTo(unit::class, 'uom', 'id');
    }

    public function servicePartAdjustments()
    {
        return $this->hasMany(ServicePartadjustMent::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(WarHouse::class); 
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
    public function u_o_m()
    {
        return $this->belongsTo(UOM::class, 'uom', 'id');
    }
    
}