<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class VehicleMasterResource extends JsonResource
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
            'registeration' => $this->rego,
            'customer_id' => $this->client,
            'make_id' => $this->v_make,
            'model_id' => $this->vm,
            'engine_spec_id' => $this->es_id,
            'regional_spec_id' => $this->rs_id,
            'state' => $this->state,
            'yearseries' => $this->model_series,
            'vin' => $this->vin,
            'odometer' => $this->odometer,
            'uom' => $this->uom,
            'parent_id' => $this->parent_id,			
            'isModified' => (bool) $this->isModified,
            'isDeleted' => (bool) $this->isDeleted,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,           
        ];
    }
}
