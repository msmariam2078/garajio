<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warrenty_extend extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'customer_id','product_id','purchase_date','coverage_type','price','status','extend_start_date','extend_end_date','duration','created_at','updated_at'
    ];
    public function servicePart(){
        return $this->belongsTo(ServicePart::class, 'product_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}