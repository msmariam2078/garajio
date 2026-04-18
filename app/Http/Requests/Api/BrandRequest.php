<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
		$brandId = $this->input('brand_id');
        return [
            'brand_id' => 'required|exists:brands,id',
            'description' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'description')->ignore($brandId),
            ],
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