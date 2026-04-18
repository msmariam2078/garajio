<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'type' => $this->type,
            'isModified' => (bool) $this->isModified,
            'country' => $this->country,
            'country_code' => $this->ccm,
            'gst' => $this->gst,
            'client_type' => $this->client_type,
            'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,
            'isDeleted' => (bool) $this->isDeleted,
            'addresses' => $this->clientDetails->service_address ?? null,
            'city' => $this->clientDetails->service_city ?? null,
            'post_code' => $this->clientDetails->service_zip_code ?? null,
            'status' => (bool) $this->is_active,
            'customer_template' => $this->customertemplate->code ?? null,
        ];
    }
}
