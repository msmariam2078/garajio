<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];   

    public function clientInfo()
    {
        return $this->belongsTo(User::class, 'client', 'id')->withDefault();
    }
	public function Client()
    {
        return $this->belongsTo(User::class, 'client', 'id');
    }
        public function vehicle_makes()
    {
        return $this->belongsTo(vehicle_make::class, 'v_make', 'id');
    }
        public function vehicle_models()
    {
        return $this->belongsTo(vehicle_model::class, 'vm', 'id');
    }
        public function vehicle_model_codes()
    {
        return $this->belongsTo(vehicle_model_code::class, 'vmc', 'id');
    }
    public function vehicle_trans()
    {
        return $this->belongsTo(vehicle_tran::class, 'vt', 'id');
    }
    public function vehicle_driving_types()
    {
        return $this->belongsTo(vehicle_driving_type::class, 'vdt', 'id');
    } 
    public function vehicle_fuel_types()
    {
        return $this->belongsTo(vehicle_fuel_type::class, 'vft', 'id');
    }
    public function vehicle_body_types()
    {
        return $this->belongsTo(vehicle_body_type::class, 'vbt', 'id');
    }
    public function vehicle_colour_news()
    {
        return $this->belongsTo(vehicle_colour_new::class, 'vcn', 'id');
    }
    public function vehicle_seats()
    {
        return $this->belongsTo(vehicle_seat::class, 'vsc', 'id');
    }
    
    public function warrantyRegistrations()
    {
        return $this->hasMany(Warranty_registration::class, 'vehicle_id', 'id');
    }
	
	public function engineSpecs()
    {
        return $this->belongsTo(EngineSpecs::class, 'es_id', 'id')->withDefault();
    }
    


    public static $brand = [
        'Suzuki' => 'Suzuki',
        'Audi' => 'Audi',
        'BMW' => 'BMW',
        'Honda' => 'Honda',
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

   
}