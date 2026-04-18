<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class VehicleMakeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'make_name'       => 'required|string|max:255 | unique:vehicle_makes,make_name',
           
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