<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItems extends Model
{
    use HasFactory;

    public function bookingQuotation()
    {
    return $this->belongsTo(BookingQuotation::class, 'quotation_id');
    }

}