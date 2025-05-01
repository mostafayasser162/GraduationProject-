<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Validation\Rule;
use Validator;

class PhoneRule implements InvokableRule
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
        // dd($attribute);
        // $saudiPhone = new SaudiPhone;
        $uniquePhone = Rule::unique($this->table, 'phone');
        if ($this->id != null) {
            $uniquePhone = $uniquePhone->ignore($this->id);
        } else {
            $uniquePhone = $uniquePhone->whereNull('deleted_at');
        }
        $otherRules = [];
        if ($this->unique) {
            $otherRules = array_merge($otherRules, [$uniquePhone]);
        }

        foreach ($otherRules as $rule) {
            $validator = Validator::make([$attribute => $value], [$attribute => $rule]);
            if ($validator->fails()) {
                return $fail(__('auth.invalid_phone'));
            }
        }

        // if (! $uniquePhone->passes($attribute, $value) && $this->unique) {
        //     return $fail($uniquePhone->message());
        // }

        return true;
    }
}
