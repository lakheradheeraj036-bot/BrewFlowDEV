<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isSuperAdmin();
    }

    public function rules(): array
    {
        $businessId = $this->route('business')->id;

        return [
            'name'                 => ['required', 'string', 'min:2', 'max:100', "unique:businesses,name,{$businessId}"],
            'type'                 => ['required', 'in:cafe,hotel,restaurant,bar,other'],
            'email'                => ['required', 'email', 'max:150', "unique:businesses,email,{$businessId}"],
            'phone'                => ['nullable', 'string', 'max:20'],
            'address'              => ['nullable', 'string', 'max:255'],
            'city'                 => ['nullable', 'string', 'max:100'],
            'country'              => ['nullable', 'string', 'max:100'],
            'description'          => ['nullable', 'string', 'max:2000'],
            'logo'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'subscription_plan'    => ['nullable', 'in:free,starter,professional,enterprise'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'status'               => ['required', 'in:active,inactive,pending,suspended'],
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

    public function prepareForValidation()
    {
        $this->merge([
            'name' => strip_tags(trim($this->name)),
            'email' => filled($this->email) ? strtolower(trim($this->email)) : null,
            'phone' => filled($this->phone) ? trim($this->phone) : null,
            'address' => filled($this->address) ? strip_tags(trim($this->address)) : null,
            'city' => filled($this->city) ? strip_tags(trim($this->city)) : null,
            'country' => filled($this->country) ? strip_tags(trim($this->country)) : null,
            'description' => filled($this->description) ? strip_tags(trim($this->description)) : null,
        ]);
    }
}
