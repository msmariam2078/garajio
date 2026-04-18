<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_editable_point extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'point_des',
        'points',
     
    ];
}
