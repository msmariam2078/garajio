<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class CustomerTemplateResource extends JsonResource
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
            'code' => $this->code,
            'description' => $this->description,
            'contact_type' => $this->contact_type,
            'isModified' => (bool) $this->isModified,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,           
        ];
    }
}
