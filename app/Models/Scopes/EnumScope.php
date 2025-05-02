<?php

namespace App\Models\Scopes;

// use App\Enums\Enum;

use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Validation\Rules\Enum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Spatie\Enum\Laravel\Enum as LaravelEnum;

class EnumScope implements Scope
{
    public function __construct(public $column, public LaravelEnum $enum) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($this->column, $this->enum);
    }
}
