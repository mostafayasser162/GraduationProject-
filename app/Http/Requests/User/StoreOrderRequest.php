<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cash,visa',
            'card_number' => 'digits:16',
            'expiry_date' => 'date_format:m/y',
            'cvv' => 'digits:3',
            'second_phone' => ['required', 'regex:/^01[0-9]{9}$/'],
        ];
    }
}
