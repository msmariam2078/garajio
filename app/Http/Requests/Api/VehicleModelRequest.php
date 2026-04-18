<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule; // ✅ Correct import added here

class VehicleModelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       
        $vehicleModelId = $this->input('make_id'); 
    
        return [
            'make_id' => 'required|exists:vehicle_makes,id',
            'model_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicle_models')->where(function ($query) use ($vehicleModelId) {
                    return $query->where('make_id', $this->make_id)
                                 ->where('id', '!=', $vehicleModelId); 
                }),
            ],
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