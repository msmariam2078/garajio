<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WarHouse;

class ServicePartadjustMent extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_part_id',
        'warehouse_id',
        'commited',
        'available',
        'unavailable',
        'onhand'
    ];

    public function warehouse()
    {
        return $this->belongsTo(WarHouse::class, 'warehouse_id');
    }

    public function servicePart()
    {
        return $this->belongsTo(ServicePart::class, 'service_part_id');
    }

    public function servicePartWithV()
    {
        return $this->belongsTo(ServicePartwithV::class, 'service_part_id'); 
    }
}
