<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    public function customerdetails()
    {
        return $this->belongsTo(User::class,'customer');
    }
           public function bookingInfo()
    {
        return $this->belongsTo(Booking::class, 'booking');
    }

    public function vehicledetails()
    {
        return $this->belongsTo(Vehicle::class, 'equipment');
    }
     public function template()
    {
        return $this->belongsTo(TempInspection::class, 'templates');
    }
        public function mainpoints()
    {
        return $this->hasMany(GroupPoint::class, 'inspection_id');
    }

}
