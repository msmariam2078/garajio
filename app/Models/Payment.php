<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model

{
	use HasFactory;
	protected $guarded = [];
	public function user()
	{
		return $this->belongsTo(User::class, 'client');
	}
	public function invoices()
	{
		return $this->belongsTo(Invoice::class, 'invoice');
	}
	public function workorder()
	{
		return $this->belongsTo(WorkOrder::class, 'workorder');
	}
}
