<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class VehicleDrivingTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'driving_type'       => 'required|string|max:255',
           
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