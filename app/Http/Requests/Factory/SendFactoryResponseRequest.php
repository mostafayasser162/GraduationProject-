<?php

namespace App\Http\Requests\Factory;

use Illuminate\Foundation\Http\FormRequest;

class SendFactoryResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'request_id' => ['required', 'exists:requests,id'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'image' => ['nullable', 'image'],
        ];
    }
}
