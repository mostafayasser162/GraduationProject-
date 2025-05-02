<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Validation\Rule;
use Validator;

class UniqueEmail implements InvokableRule
{
    /**
     * Create a new rule instance.
     *
     * @param  string  $model
     * @return void
     */
    public function __construct(public $table = 'users', public $id = null, protected bool $unique = true)
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function __invoke($attribute, $value, $fail)
    {
        $uniqueEmail = Rule::unique($this->table, 'email');
        if ($this->id != null) {
            $uniqueEmail = $uniqueEmail->ignore($this->id);
        } else {
            $uniqueEmail = $uniqueEmail->whereNull('deleted_at');
        }
        $validator = Validator::make([$attribute => $value], [$attribute => $uniqueEmail]);
        if ($validator->fails()) {
            return $fail(('invalid_email'));
        }

        return true;
    }
}
