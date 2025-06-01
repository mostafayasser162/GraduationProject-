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
                'unique:startups,phone,',
            ],
            'description' => 'required|string|max:255',
            'social_media_links' => 'nullable|array',
            'social_media_links.*' => 'required|url',
            'package_id'         => 'required|exists:packages,id',
            'logo' =>    'required|file|mimes:jpeg,png,jpg',
            // 'categories_id'      => 'required|exists:categories,id',
            'email' => ['required', new UniqueEmail('startups'), ...Rules::get('email')],
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'],
            'payment_method' => 'required|string|max:255',
            'payment_account' => 'required|string|max:255',
        ];
    }
}
