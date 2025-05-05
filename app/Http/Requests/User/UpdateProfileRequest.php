<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $this->user()->id,
            'phone' => 'string|max:20,unique:users,phone,' . $this->user()->id,
            'password' => 'string|min:8|confirmed',
        ];
    }
}

