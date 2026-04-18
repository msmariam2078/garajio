<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends Model
{
	use HasFactory;
	protected $fillable = [
		'created_date',
		'subject',
		'customer_id',
		'vehicle',
		'service_group',
		'service_location',
		'inspection',
		'effort_hours',
		'status',
		'service_lat',
		'service_lng',
		'technician',
		'booking'
	];

	public function client()
	{
		return $this->belongsTo(User::class, 'customer_id')->withDefault();
	}

	public function agent()
	{
		return $this->belongsTo(User::class, 'created_by')->withDefault();
	}

	public function inv()
	{
		return $this->hasOne(Invoice::class, 'wo_id');
	}

	public function WOServiceParts()
	{
		return $this->hasMany(WOServicePart::class, 'wo_id');
	}

	public function Workorder_scraps()
	{
		return $this->hasMany(Workorder_scrap::class, 'wo_id');
	}

	public function products()
	{
		return $this->belongsToMany(ServicePart::class, 'w_o_service_parts', 'wo_id');
	}

	public function serviceGroup()
	{
		return $this->belongsToMany(ServiceGroups::class, 'service_group', 'id');
	}

	public function bookingInfo()
	{
		return $this->belongsTo(TechnicianBookingAppointment::class, 'id', 'workorder_id');
	}

	public function bookings()
	{
		return $this->hasOne(Booking::class, 'workorderid', 'id');
	}

	public function vehicl()
	{
		return $this->belongsTo(Vehicle::class, 'vehicle');
	}

	public function vehicle()
	{
		$vehicleIds = json_decode($this->vehicle, true);
		$vehicleId = is_array($vehicleIds) && count($vehicleIds) > 0 ? $vehicleIds[0] : null;

		return Vehicle::find($vehicleId);
	}

	public function booking_quotation()
	{
		return $this->hasOne(BookingQuotation::class, 'workorder_id');
	}
	// public function booking_items()
	// {
	// 	return $this->belongsToMany(BookingItems::class, 'quotation_id');
	// }

	public function appointment()
	{
		return $this->hasOne(TechnicianBookingAppointment::class, 'workorder_id')->withDefault();
	}

	public function getTechnicianUserAttribute()
	{
		$id = \Illuminate\Support\Arr::first(json_decode($this->technician, true) ?? []);
		return $id ? \App\Models\User::find($id) : null;
	}

	public function getBookingRecordAttribute()
	{
		$id = \Illuminate\Support\Arr::first(json_decode($this->booking, true) ?? []);
		return $id ? \App\Models\Booking::find($id) : null;
	}

	// Bookings (assuming single booking)
	public function bookingRecord()
	{
		return $this->hasOne(Booking::class, 'id', 'booking');
		// if `booking` is JSON array, see below
	}

	// Vehicle (single vehicle from JSON array)
	public function vehicleRecord()
	{
		$vehicleIds = json_decode($this->vehicle, true);
		$vehicleId = (is_array($vehicleIds) && count($vehicleIds) > 0) ? $vehicleIds[0] : null;

		return $vehicleId ? Vehicle::with('vehicle_models')->find($vehicleId) : null;
	}

	// Technicians (single for now, from JSON)
	public function technicianRecord()
	{
		$techIds = json_decode($this->technician, true); // decode JSON as array
		$techId = (is_array($techIds) && count($techIds) > 0) ? $techIds[0] : null;

		return $techId ? User::find($techId) : null;
	}

	public function serviceGroupRecord()
	{
		$groupIds = json_decode($this->service_group, true); // decode JSON array
		$groupId = (is_array($groupIds) && count($groupIds) > 0) ? $groupIds[0] : null;

		return $groupId ? ServiceGroups::find($groupId) : null;
	}
}
