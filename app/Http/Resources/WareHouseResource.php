<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WareHouseResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'state' => $this->state,
            'isModified' => $this->isModified,
            'isDeleted' => $this->isDeleted,
            'IsBCToPortalIntegrated' => $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,
        ];
    }
}
