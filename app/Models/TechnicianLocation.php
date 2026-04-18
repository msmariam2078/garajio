<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','home_location','lat','long','working_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
