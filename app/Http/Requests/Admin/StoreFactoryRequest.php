<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Factory\Status;

class StoreFactoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:factories,phone',
            'email' => 'nullable|email|unique:factories,email',
            'password' => 'required|string|min:6',
            'payment_methods' => 'required|string',
            'payment_account' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
