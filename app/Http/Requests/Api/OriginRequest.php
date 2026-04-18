<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class OriginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'description' => 'required|string|max:255|unique:origins,description',
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