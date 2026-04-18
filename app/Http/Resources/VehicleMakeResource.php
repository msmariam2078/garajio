<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class VehicleMakeResource extends JsonResource
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
            'make_name' => $this->make_name,
            'isModified' => (bool) $this->isModified,
            'isDeleted' => (bool) $this->isDeleted,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime, 
                     
        ];
    }
}
