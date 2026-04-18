<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingQuotation extends Model
{
	use HasFactory;
	protected $guarded = [];

	public function items()
	{
		return $this->hasMany(BookingItems::class, 'quotation_id');
	}

	public function agent()
	{
		return $this->belongsTo(User::class, 'created_by')->withDefault();
	}

	public function customer()
	{
		return $this->belongsTo(User::class, 'customer_id', 'id');
	}


	public function booking()
	{
		return $this->belongsTo(Booking::class, 'booking_id', 'id');
	}
}
