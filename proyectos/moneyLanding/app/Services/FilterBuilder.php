<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class FilterBuilder
{
    protected const ALLOWED_OPERATORS = ['=', '!=', '>', '<', '>=', '<=', 'like', 'not like'];

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

            if (!in_array($operator, self::ALLOWED_OPERATORS, true)) {
                throw new \InvalidArgumentException("Invalid operator: {$operator}");
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
