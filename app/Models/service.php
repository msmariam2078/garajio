<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    public static $types = [
        'Inventory' => 'Inventory',
        'Service' => 'Service',
        'Non-Inventory' => 'Non-Inventory',
    ];

    public static $availability = [
        'Stock' => 'Stock',
        'Non-Stock ' => 'Non-Stock ',
    ];
}
