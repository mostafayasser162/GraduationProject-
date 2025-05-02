<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ExcludeRelated implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! empty($builder->getQuery()->columns)) {
            return;
        }
        $columns = Cache::rememberForever($model->getTable(), function () use ($model) {
            return Schema::getColumnListing($model->getTable());
        });
        $columns = array_diff($columns, ['related']);
        $columns = array_map(fn ($column) => $model->getTable().'.'.$column, $columns); // example: ['orders.id', 'orders.user_id', ...]

        $builder->select($columns);
    }
}
