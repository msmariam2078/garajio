<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'client');
    }
     public function supervisorinfo()
    {
        return $this->belongsTo(User::class, 'supervisor');
    }
	public function agent()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    public function technicianInfo()
    {
        return $this->belongsTo(User::class, 'technician');
    }

    public function vehicless()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle');
    }
    public function servicegroupdata()
    {
        return $this->belongsTo(ServiceGroups::class, 'service_group');
    }
    public function serviceGroup()
    {
        return $this->belongsTo(ServiceGroup::class, 'service_group', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Booking_comment::class,'booking_id');
    }
    public function skillGroup()
    {
        return $this->belongsTo(SkillGroup::class, 'skill_group', 'id');
    }
    public function bookingquotation()
    {
		return $this->hasOne(BookingQuotation::class, 'booking_id', 'id');
	}
}
