<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,  
            'product_name' => $this->product_name, 
            'qty' => $this->qty,  
            'gstprice' => $this->gstprice, 
            'linetotal' => $this->linetotal, 
            'warranty' => $this->warranty,
        ];
    }
}
