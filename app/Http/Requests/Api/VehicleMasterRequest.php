<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VehicleMasterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules()
	{
		return [
			'rego' => 'nullable|string|max:255',
			'client' => 'required|integer|exists:users,id',
			'v_make' => 'required|integer|exists:vehicle_makes,id',
			'vm' => 'required|integer|exists:vehicle_models,id',
			'es_id' => [
				'nullable',
				'integer',
				Rule::exists('engine_specs', 'id')
				->where('make_id', $this->input('v_make'))
				->where('model_id', $this->input('vm')),
			],
			'rs_id' => 'nullable|integer|exists:regional_specs,id',
			'state' => 'required|string|max:100',
			'model_series' => 'nullable|string|max:100',
			'vin' => 'required|string|max:50',
			'odometer' => 'required|integer|min:0',
			// 'uom' => 'nullable|integer|exists:u_o_m_s,id',
			'uom' => 'nullable|string',
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
