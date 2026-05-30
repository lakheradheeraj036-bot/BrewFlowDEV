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
            'name'                 => ['required', 'string', 'min:2', 'max:100', 'unique:businesses,name'],
            'type'                 => ['required', 'in:cafe,hotel,restaurant,bar,other'],
            'email'                => ['required', 'email', 'max:150', 'unique:businesses,email'],
            'phone'                => ['nullable', 'string', 'max:20'],
            'address'              => ['nullable', 'string', 'max:255'],
            'city'                 => ['nullable', 'string', 'max:100'],
            'country'              => ['nullable', 'string', 'max:100'],
            'description'          => ['nullable', 'string', 'max:2000'],
            'logo'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'subscription_plan'    => ['nullable', 'in:free,starter,professional,enterprise'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'status'               => ['required', 'in:active,inactive,pending'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                => 'Business name is required.',
            'name.min'                     => 'Business name must be at least 2 characters.',
            'name.max'                     => 'Business name may not exceed 100 characters.',
            'name.unique'                  => 'A business with this name already exists.',
            'type.required'                => 'Please select a business type.',
            'type.in'                      => 'Selected business type is invalid.',
            'email.required'               => 'Business email is required.',
            'email.email'                  => 'Please enter a valid email address.',
            'email.max'                    => 'Email address may not exceed 150 characters.',
            'email.unique'                 => 'This email address is already registered to another business.',
            'phone.max'                    => 'Phone number may not exceed 20 characters.',
            'description.max'              => 'Description may not exceed 2000 characters.',
            'logo.image'                   => 'Logo must be an image file.',
            'logo.mimes'                   => 'Logo must be a file of type: jpeg, png, jpg, svg, webp.',
            'logo.max'                     => 'Logo may not be larger than 2MB.',
            'subscription_plan_id.exists'  => 'Selected subscription plan is invalid.',
            'status.required'             => 'Please select a status.',
            'status.in'                   => 'Selected status is invalid.',
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
