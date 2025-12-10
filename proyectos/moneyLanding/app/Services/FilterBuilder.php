<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class FilterBuilder
{
    public function apply(Builder $query, array $filters): Builder
    {
        $mode = $filters['mode'] ?? 'and';
        $conditions = $filters['conditions'] ?? [];

        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? null;
            $operator = $condition['operator'] ?? '=';
            $value = $condition['value'] ?? null;

            if (!$field) {
                continue;
            }

            $callback = fn ($q) => $q->where($field, $operator, $value);

            if ($mode === 'or') {
                $query->orWhere($callback);
            } else {
                $query->where($callback);
            }
        }

        return $query;
    }
}
