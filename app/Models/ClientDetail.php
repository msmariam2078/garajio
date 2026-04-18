<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'user_id',
        'trading_name',
        'business_name',
        'company',
        'service_address',
        'service_city',
        'service_state',
        'service_country',
        'service_zip_code',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zip_code',
        'addresses',
        'parent_id',
        'type',
        'virtual',
        'credit_limit',
        'payment_terms_code',
        'payment_method_code',
        'vat_bus_posting_group',
        'customer_posting_group',
        'gen_bus_posting_group',
        'note_customer',
        'note_contact',
    ];
}
