<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class ItemMasterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'item_no' => 'required|string|max:100',
            'product_name' => 'required|string|max:255',
			'category' => 'required|integer|exists:categories,id',
            'item_type' => 'required|string|max:100',
            // 'quantity' => 'required|integer|min:0',
            'uom' => 'required|numeric|max:50',
            'sales_price' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'image' => 'nullable',
            'brand' => 'integer|exists:brands,id',
            'origin' => 'integer|exists:origins,id',
            'reference_type' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'isModified' => 'nullable|boolean',
            'isDeleted' => 'nullable|boolean',
            'IsBCToPortalIntegrated' => 'nullable|boolean',
            'BCToPortalIntegratedTime' => 'nullable|date',
            'IsPortalToBCIntegrated' => 'nullable|boolean',
            'PortalToBCIntegratedTime' => 'nullable|date',
        ];
    }
    

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::json([
            'status'  => false,
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
