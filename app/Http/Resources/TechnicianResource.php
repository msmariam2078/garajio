<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class TechnicianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {       

        return [
            'user_id' => $this->id,
            'employee_title' => $this->employee_title,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => '+' .preg_replace('/[^0-9]/', '',  $this->ccm). $this->phone_number,
            'profile_picture' => $this->profile,
            'addresses' => $this->clients->service_address ?? null,
            'city' => $this->clients->service_city ?? null,
            'post_code' => $this->clients->service_zip_code ?? null,
            'country' => $this->country,
            'job_title' => $this->job_title,
            'isModified' => (bool) $this->isModified,
            'isDeleted' => (bool) $this->isDeleted,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,
        ];
    }
}
