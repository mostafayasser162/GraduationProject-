<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UniquePhone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected ?Model $model,
        protected string $phoneField = 'phone',
        protected string $countryCodeField = 'country_code')
    {
        //
    }

    public function passes($attribute, $value)
    {
        return ! $this->model ?? true;
    }

    public function message(): string
    {
        return __('validation.unique', ['attribute' => 'phone']);
    }
}
