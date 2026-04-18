<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class ServicePartAdjustmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_id'       => 'required|exists:war_houses,id',
            'service_part_id'       => 'required|exists:service_parts,id',
            'onhand'       => 'required',
            'isModified' => 'nullable|boolean',
            'isDeleted' => 'nullable|boolean',
            'IsBCToPortalIntegrated' => 'nullable|boolean',
            'BCToPortalIntegratedTime' => 'nullable|date',
            'IsPortalToBCIntegrated' => 'nullable|boolean',
            'PortalToBCIntegratedTime' => 'nullable|date',
           
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::json([
            'status' => false,
            'message' => $validator->errors()->first()
        ], 422));
    }
}