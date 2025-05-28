<?php

namespace App\Http\Requests\StartUp;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\User\Traits\HasUser;
use App\Rules\Rules;
use App\Rules\UniqueEmail;

/**
 * @property string $phone
 * @property string $country_code
 */
class RegisterRequest extends FormRequest
{
    use HasUser;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules(): array
    {
        return [
            'name' => ['required', ...Rules::get('user.name')],
            'phone' => [
                'required',
                'unique:users,phone,',
            ],
            'email' => ['required', new UniqueEmail('users'), ...Rules::get('email')],
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'],

        ];
    }
}
