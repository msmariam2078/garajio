<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class CustomerRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		$userId = $this->route('id') ?? $this->customer_id;

		return [
			'first_name'                => 'required|string|max:255',
			'last_name'                 => 'nullable|string|max:255',
			'phoneno'                   => 'required|string|max:20|unique:users,phone_number,' . $userId,
			'addresses'                 => 'required|string|max:255',
			'email'                     => 'nullable|email|max:255|unique:users,email,' . $userId,
			'country'                   => 'required|string|max:100',
			'country_code'              => 'required|string|max:100',
			'gstorvatno' => 'exclude_if:client_type,individual|nullable|string|max:50',
			'IsBCToPortalIntegrated'    => 'nullable|boolean',
			'BCToPortalIntegratedTime'  => 'nullable|date',
			'IsPortalToBCIntegrated'    => 'nullable|boolean',
			'PortalToBCIntegratedTime'  => 'nullable|date',
			'city'                      => 'nullable|string',
			'post_code'                 => 'nullable|string',
			'customer_template'         => 'nullable|integer',
			'client_type'             => 'required|in:corporate,individual',
            'status' => 'nullable|boolean',
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
