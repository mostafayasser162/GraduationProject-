<?php

namespace App\Http\Requests\Factory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $factoryId = auth()->user()->id;

        return [
            'name'             => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email|unique:factories,email,' . $factoryId,
            'payment_methods'  => 'nullable|string',
            'payment_account'  => 'nullable|string',
            'description'      => 'nullable|string',
            'password'         => 'nullable|string|min:6|confirmed',
        ];
    }
}
