<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'customer_id' => $this->client,
            'vehicle_id' => $this->vehicle,
            'requested_date' => $this->requested_date,
            'requested_time' => $this->requested_time,
            'booking_date' => $this->booking_date,
            'booking_time' => $this->booking_time,
            'due_date' => $this->due_date,
            'service_group' => [
                'id' => $this->service_group_id,
                'name' => $this->service_group_name,
            ],
            'status' => $this->status,
            'service_location' => $this->service_location,
            'landmark' => $this->landmark,
            'description' => $this->description,
            'quotations' => [
                [
                    'quotation_id' => $this->quotation_id,
                    'status' => $this->quotation_status,
                ]
            ],
            'items' => [
                [
                    'product_id' => $this->product_id,
                    'product_name' => $this->product_name,
                    'qty' => $this->qty,
                    'gstprice' => $this->gstprice,
                    'linetotal' => $this->linetotal,
                    'warrenty' => $this->warrenty,
                    'isModified' =>  (bool)$this->biisModified,  
                    'isDeleted' =>  (bool)$this->biisDeleted,  
                    'isBCToPortalIntegrated' =>  (bool)$this->biIsBCToPortalIntegrated, 
                    'BCToPortalIntegratedTime' => $this->biBCToPortalIntegratedTime, 
                    'isPortalToBCIntegrated' =>  (bool)$this->biIsPortalToBCIntegrated, 
                    'PortalToBCIntegratedTime' => $this->biPortalToBCIntegratedTime, 
                ]
            ],
    
            'isBookingModified' =>  (bool)$this->isModified,  
            'isBookingDeleted' =>  (bool)$this->isDeleted,  
            'isBookingBCToPortalIntegrated' =>  (bool)$this->IsBCToPortalIntegrated, 
            'bookingBCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,  
            'isBookingPortalToBCIntegrated' =>  (bool)$this->IsPortalToBCIntegrated,  
            'bookingPortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,  
        ];
    }
    
   
}



    
