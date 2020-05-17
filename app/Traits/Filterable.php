<?php

namespace App\Traits;

trait Filterable
{
    public static function filter($query, array $filters = [])
    {
        $class = static::class;
        if (isset($class::$filterable)) {
            foreach ($filters as $key => $rawValue) {
                if (!is_array($rawValue)) {
                    $values = [$rawValue];
                    $where = 'where';
                } else {
                    $values = $rawValue;
                    $where = 'orWhere';
                }
                foreach ($values as $value) {
                    if (isset($class::$filterable[$key])) {
                        if (empty($class::$filterable[$key])) {
                            $query->$where($key, '=', $value);
                        } elseif (
                            is_array($class::$filterable[$key])
                            && isset($class::$filterable[$key]['field'])
                            && !isset($class::$filterable[$key]['relationships'])
                        ) {
                            $query->$where($class::$filterable[$key]['field'], '=', $value);
                        } else {
                            if (!is_array($class::$filterable[$key])) {
                                $class::$filterable[$key] = [$class::$filterable[$key]];
                            }
                            $field = isset($class::$filterable[$key]['field'])
                                ? $class::$filterable[$key]['field']
                                : $key;
                            $relationships = isset($class::$filterable[$key]['relationships'])
                                ? $class::$filterable[$key]['relationships']
                                : $class::$filterable[$key];
                            $query->$where(function ($query) use ($field, $relationships, $value) {
                                $firstRun = true;
                                foreach ($relationships as $relation) {
                                    $method = $firstRun ? 'whereHas' : 'orWhereHas';
                                    if ($firstRun) {
                                        $firstRun = false;
                                    }
                                    $query->$method($relation, function ($query) use ($field, $value) {
                                        $query->where($field, '=', $value);
                                    });
                                }
                            });
                        }
                    }
                }
            }
        }

        return $query;
    }
}
