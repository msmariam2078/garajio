<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class EngineSpecsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    $engineSpecsId = $this->input('engine_specs_id');

    return [
        'make_id' => [
            'required',
            'integer',
            'exists:vehicle_makes,id',
        ],
		
		'model_id' => [
			'required',
			'integer',
			Rule::exists('vehicle_models', 'id')->where('make_id', $this->input('make_id')),
		],

        'enginespecs' => [
            'required',
            'string',
            'max:255',
            Rule::unique('engine_specs')
                ->where(function ($query) {
                    return $query->where('make_id', $this->input('make_id'))
                                 ->where('model_id', $this->input('model_id'));
                })->ignore($this->input('engine_specs_id')),
        ],
    ];
}
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], 422));
    }
}