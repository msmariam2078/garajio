<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOType extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'parent_id',
    ];
    public static $uom = [
        'Kms' => 'Kms',
        'Miles' => 'Miles',
    ];
    public static $emirates = [
        'Abu Dhabi' => 'Abu Dhabi',
        'Dubai' => 'Dubai',
        'Sharjah' => 'Sharjah',
        'Ajman' => 'Ajman',
        'Umm Al Quwain' => 'Umm Al Quwain',
        'Ras Al Khaimah' => 'Ras Al Khaimah',
        'Fujairah' => 'Fujairah',
        'Others' => 'Others',
    ];

    public static $ship_to = [
        'Default(Sell-to-Address)' => 'Default(Sell-to-Address)',
        'Alternate Shipping Address' => 'Alternate Shipping Address',
        'Custom Address' => 'Custom Address',
    ];

    public static $bill_to = [
        'Default(Customer)' => 'Default(Customer)',
        'Another Customer' => 'Another Customer',
        'Custom Address' => 'Custom Address',
    ];

}
