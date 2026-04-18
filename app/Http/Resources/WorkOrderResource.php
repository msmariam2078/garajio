<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class WorkOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this[0]->id,
            'customer_id' => $this[0]->customer_id,
            'vehicle_id' => json_decode($this[0]->vehicle, true),
            'created_time' => $this[0]->created_date,
            'service_address' => $this[0]->service_location,
            'technician' => $this[0]->technician ? json_decode($this[0]->technician, true) : null,
            'isModified' => (bool) $this[0]->isModified,
            'isDeleted' => (bool) $this[0]->isDeleted,
            'isBCToPortalIntegrated' => (bool) $this[0]->IsBCToPortalIntegrated,
            'isBCToPortalIntegratedTime' => $this[0]->BCToPortalIntegratedTime,
            'isPortalToBCIntegrated' => (bool) $this[0]->IsPortalToBCIntegrated,
            'isPortalToBCIntegratedTime' => $this[0]->PortalToBCIntegratedTime,
    
            'inv' => $this[0]->inv ? [
                'invoice_id' => $this[0]->inv->id,
                'status' => $this[0]->inv->status,
                'due_date' => $this[0]->inv->due_date,
                'posting_date' => $this[0]->inv->invoice_date,
                'invoice_date' => $this[0]->inv->invoice_date,
            ] : null,
    
            'items' => $this->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'qty' => $item->qty,
                    'unit_price' => number_format($item->unit_price, 2),
                    'discountpercentage' => intval($item->gstprice),
                    'linetotal' => number_format($item->linetotal, 2),
                    'warrenty' => $item->warrenty,
                    'location' => $item->location,
                    'taxpercentage' => intval($item->taxpercentage),
                    'taxamount' => $item->taxamount,
                    'totalamount' => number_format($item->totalamount, 2),
                    'uom' => $item->uom,
                    'isBCToPortalIntegrated' => (bool) $item->biIsBCToPortalIntegrated,
                    'BCToPortalIntegratedTime' => $item->biBCToPortalIntegratedTime,
                    'isPortalToBCIntegrated' => (bool) $item->biIsPortalToBCIntegrated,
                    'PortalToBCIntegratedTime' => $item->biPortalToBCIntegratedTime,
                ];
            })->values(),
        ];
    }
    
    

    
    
   
}



    