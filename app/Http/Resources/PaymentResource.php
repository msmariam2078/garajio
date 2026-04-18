<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
{
  
    $paymentMethods = explode(',', $this->payment_method);
    $amounts = explode(',', $this->paid_amount);


    $paymentDetails = [];
    foreach ($paymentMethods as $index => $method) {
        $paymentDetails[] = [
            'method' => $method,
            'amount' => $amounts[$index],
        ];
    }

    return [
        'id' => $this->id,
        'invoice_id' => $this->invoice,
        'customer_id' => $this->client,
        'payment_date' => $this->payment_date,
        'payment_ref_no' => '#PAY' . $this->id,
        'payment_methods' => $paymentDetails,  
        'isModified' => (bool) $this->isModified,
        'isDeleted' => (bool) $this->isDeleted,
        'IsBCToPortalIntegrated' => (bool) $this->IsBCToPortalIntegrated,
        'BCToPortalIntegratedTime' => $this->BCToPortalIntegratedTime,
        'IsPortalToBCIntegrated' => (bool) $this->IsPortalToBCIntegrated,
        'PortalToBCIntegratedTime' => $this->PortalToBCIntegratedTime,
    ];
}

}
