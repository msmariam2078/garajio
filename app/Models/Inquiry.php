<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'date',
        'description',
        'estimation_id',
        'appointment_date',
        'appointment_time',
        'appointment_note',
        'work_order_id',
        'address',
        'status',
        'representative',
        'requires_followup',
        'customer',
        'location',
        'note',
        'location_text',
    ];

    public static $status = [
        'Estimate' => 'Estimate',
        'In Progress' => 'In Progress',
        'Completed' => 'Completed',
    ];

    public static $location = [
        'fixed' => 'Fixed',
        'mobile' => 'Mobile',
        'virtual' => 'Virtual',
    ];

    public static $time = [
        'any_time' => 'Any Time',
        'morning' => 'Morning',
        'afternoon' => 'Afternoon',
        'evening' => 'Evening',
    ];

    public function estimation()
    {
        return $this->belongsTo(Estimation::class);
    }

    public function vehicle_data()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle', 'id');
    }

    public function work_order()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id', 'id');
    }
}
