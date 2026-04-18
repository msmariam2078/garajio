<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BookingItemResource;

class BookingQuotationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'quotation_id' => $this->id,
            'status' => $this->status,  // Quotation status
            'items' => BookingItemResource::collection($this->items),  // Items related to this quotation
        ];
    }
}
