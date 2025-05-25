<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SearchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {

        $request = app('request');

        if (!$request->has('search')) {
            return;
        }
        if (!auth()->check() && $request->bearerToken()) {
            try {
                auth()->authenticate();
            } catch (\Exception $e) {
                return;
            }
        }

        $search = $request->get('search');

        $builder->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        });
    }
}
