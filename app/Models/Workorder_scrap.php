<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workorder_scrap extends Model
{
    use HasFactory;
    protected $fillable = [
        'wo_id',
        'scrap_id',
        'scrap_name',
   
    ];

 
 public function product()
    {
        return $this->belongsTo(ServicePart::class,'scrap_id');   
    }
  


}
