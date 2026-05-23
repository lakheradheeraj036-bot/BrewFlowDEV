<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'min:2', 'max:100', 'unique:businesses,name'],
            'type'              => ['required', 'in:cafe,hotel,restaurant,bar,other'],
            'email'             => ['nullable', 'email', 'max:150', 'unique:businesses,email'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:255'],
            'city'              => ['nullable', 'string', 'max:100'],
            'country'           => ['nullable', 'string', 'max:100'],
            'subscription_plan' => ['required', 'in:free,starter,professional,enterprise'],
            'status'            => ['required', 'in:active,inactive,pending'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'Business name is required.',
            'name.min'                   => 'Business name must be at least 2 characters.',
            'name.max'                   => 'Business name may not exceed 100 characters.',
            'name.unique'                => 'A business with this name already exists.',
            'type.required'              => 'Please select a business type.',
            'type.in'                    => 'Selected business type is invalid.',
            'email.email'                => 'Please enter a valid email address.',
            'email.max'                  => 'Email address may not exceed 150 characters.',
            'email.unique'               => 'This email address is already registered to another business.',
            'phone.max'                  => 'Phone number may not exceed 20 characters.',
            'subscription_plan.required' => 'Please select a subscription plan.',
            'subscription_plan.in'       => 'Selected subscription plan is invalid.',
            'status.required'            => 'Please select a status.',
            'status.in'                  => 'Selected status is invalid.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withInput()
                ->withErrors($validator, 'business')
        );
    }
}
