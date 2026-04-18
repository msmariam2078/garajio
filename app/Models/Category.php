<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category  extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    protected $table = 'categories';

   // Relationship to parent
public function parent()
{
    // Link the 'parent' column (foreign key) to the 'id' column (local key)
    return $this->belongsTo(Category::class, 'parent', 'id');
}

// Relationship to children
public function children()
{
    // Link the 'parent' column (foreign key) to the 'id' column (local key)
    return $this->hasMany(Category::class, 'parent', 'id');
}

public function serviceParts()
{
    return $this->hasMany(ServicePart::class, 'category', 'id');
}

}