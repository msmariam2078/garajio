<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianBookingAppointment extends Model
{
    use HasFactory;

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function workorder()
    {
    	return $this->belongsTo(WorkOrder::class)->withDefault();
    }

	public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id')->withDefault();   
    }	
}
