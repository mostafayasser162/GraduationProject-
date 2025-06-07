<?php

namespace App\Http\Requests\StartUp;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Rules;
use App\Rules\UniqueEmail;

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
        $userId = auth()->user()->id;
        return [
            'name' => [...Rules::get('user.name')],
            'email' => 'email|unique:startups,email,' . $userId,
            'phone' => 'string|regex:/^01[0-9]{9}$/|max:20,unique:startups,phone,' . $userId,
            'description' => 'string|max:255',
            'social_media_links' => 'nullable|array',
            'social_media_links.*' => 'url',
            'logo' =>    'file|mimes:jpeg,png,jpg',
            'password' => ['confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'],
            // 'package_id'         => 'exists:packages,id',
            // 'categories_id'      => 'exists:categories,id',
        ];
    }
}
