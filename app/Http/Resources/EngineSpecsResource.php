<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class EngineSpecsResource extends JsonResource
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
            'make_name' => $this->make->make_name,
            'model_name' => $this->model->model_name,
            'enginespecs' => $this->enginespecs,
            'isModified' => (bool) $this->isModified,
            'isDeleted' => (bool) $this->isDeleted,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime, 
                     
        ];
    }
}