<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Factory\Status;

class UpdateFactoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'nullable|email|unique:factories,email,' . $id,
            'payment_methods' => 'string',
            'payment_account' => 'string',
            'status' => ['nullable', Rule::enum(Status::class)],
            'description' => 'nullable|string',
        ];
    }
}
