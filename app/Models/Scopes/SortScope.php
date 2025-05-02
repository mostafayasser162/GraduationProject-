<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SortScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (request()->has('sort_by')) {

            $columns = Cache::rememberForever($model->getTable(), function () use ($model) {
                return Schema::getColumnListing($model->getTable());
            });
            if (in_array(request('sort_by'), $columns)) {
                $builder->getQuery()->orders = null;
                $builder->orderBy($model->getTable().'.'.request('sort_by'), request('sort_direction', 'desc'));
            }
        }
    }
}
