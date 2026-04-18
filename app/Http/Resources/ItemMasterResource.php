<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemMasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  mixed  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'item_no' => $this->item_no,
            'category' => $this->category,
            'item_type' => $this->item_type,
            // 'quantity' => $this->quantity,
            'uom' => $this->uom,
            'sales_price' => $this->sales_price,
            'brand' => $this->brand,
            'tax' => $this->tax,
            'origin' => $this->origin,
            'reference_type' => $this->reference_type,
            'reference_number' => $this->reference_number,
            'isModified' => (bool) $this->isModified,
            'isDeleted' => (bool) $this->isDeleted,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,
            'image' => $this->image,
        ];
    }
}