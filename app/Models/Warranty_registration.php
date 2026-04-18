<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty_registration extends Model
{
	use HasFactory;
	//  public $timestamps = false;

	protected $fillable = [
		'work_order_id',
		'warranty_no',
		'product_id',
		'warranty_start_date',
		'warranty_end_date',
		'warranty_period',
		'status',
		'vehicle',
		'customer_id'
	];
	public function product()
	{
		return $this->belongsTo(ServicePart::class, 'product_id', 'id');
	}

	public function workorder()
	{
		return $this->belongsTo(WorkOrder::class, 'work_order_id', 'id');
	}

	public function customer()
	{
		return $this->belongsTo(User::class, 'customer_id', 'id');
	}

	public function vehicleInfo()
	{
		return $this->belongsTo(Vehicle::class, 'vehicle', 'id')->withDefault();
	}

	public function AddWarrantyItems()
	{
		return $this->hasOne(AddWarrantyItems::class, 'wreg_id', 'id');
	}

	public function inv()
	{
		return $this->hasOne(Invoice::class, 'wo_id', 'id'); // foreign_key, local_key
	}
}
