<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class TechnicianRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $userId = $this->input('user_id');

        return [
            'user_id' => 'nullable|exists:users,id',
            'employee_title' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => $userId ? 'nullable|string|max:20' : 'required|string|max:20|unique:users,phone_number',
            'email' => $userId ? 'nullable|email|max:255' : 'required|email|max:255|unique:users,email',
            'profile_picture' => 'nullable',
            'addresses' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'isModified' => 'required|boolean',
            'isDeleted' => 'required|boolean',
            'IsBCToPortalIntegrated' => 'required|boolean',
            'BCToPortalIntegratedTime' => 'nullable|date',
            'IsPortalToBCIntegrated' => 'required|boolean',
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
