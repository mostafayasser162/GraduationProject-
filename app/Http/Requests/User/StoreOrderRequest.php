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
            'payment_method' => 'required|in:cash,visa',
            'card_number' => 'required_if:payment_method,visa|digits:16',
            'expiry_date' => 'required_if:payment_method,visa|date_format:m/y',
            'cvv' => 'required_if:payment_method,visa|digits:3',
        ];
    }
}
