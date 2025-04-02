<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Wheres
{
    public function scopeWhereDateBetweenOrEqual($query, $column, $from, $to): Builder
    {
        // if $from or $to is Y-m format, convert it to Y-m-d
        $from = strlen($from) === 7 ? $from.'-01' : $from;
        $to = strlen($to) === 7 ? Carbon::parse($to)->endOfMonth() : $to;

        return $query->whereDate($column, '>=', $from)->whereDate($column, '<=', $to);
    }

    public function scopeWhereBetweenOrEqual($query, $column, $from, $to): Builder
    {
        return $query->where($column, '>=', $from)->where($column, '<=', $to);
    }

    public function scopeWhereColumns($query, array $columns, $operator = null, $value = null): Builder
    {
        $query->where($columns[0], $operator, $value);

        foreach (array_slice($columns, 1) as $column) {
            $query->orWhere($column, $operator, $value);
        }

        return $query;
    }
}
