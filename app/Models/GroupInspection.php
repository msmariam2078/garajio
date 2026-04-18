<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupInspection extends Model
{
    use HasFactory;
     public function editpoints()
    {
        return $this->hasMany(Group_editable_point::class, 'group_id');
    }
      public function mainpoints()
    {
        return $this->hasMany(GroupPoint::class, 'group_id');
    }
}
