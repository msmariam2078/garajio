<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrentyItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'customer_id','product_id','claim_date','issued_description	','issue_proof','status','service_center','repair_date','claim_solution'
    ];
    public function servicePart(){
        return $this->belongsTo(ServicePart::class, 'product_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
