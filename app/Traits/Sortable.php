<?php

namespace App\Traits;

trait Sortable
{
    public static function sort($query, $sortField = '', $sortType = 'ASC')
    {
        $class = static::class;
        /**
         * Examples :
         * {{local_url}}/api/learners?page=1&load=all&sort=tutor.user.name&sortType=asc
         * {{local_url}}/api/learners?page=1&load=all&sort=user.name&sortType=asc
         */
        if (!empty($sortField)) {
            if (strpos($sortField, '.') === false) {
                /**
                 * Sorting local column. not a relationship
                 */
                if (!empty($sortField) && isset($class::$sortable) && in_array($sortField, $class::$sortable)) {
                    $sortType = (strtoupper($sortType) == 'DESC') ? 'DESC' : 'ASC';
                    $query = $query->orderBy($sortField, $sortType);
                }
            } else {
                /**
                 * Sorting relationships
                 * NOTE: You will need to set a function in your model to
                 * make sure the scope function is adding the necessary joins
                 * to can order by.
                 *
                 * The parameters in the url mas have
                 * Sort: {relationship.relationship.column}
                 * SortType: {ASC, DESC}
                 *
                 * In your model, you will need to have a scope function like:
                 *
                 * function scopeOrderBy{RelationshipRelationship}($query, $column, $sort_type) {}
                 *
                 */
                $sort = explode('.', $sortField);
                $column = end($sort);
                array_pop($sort);
                if (
                    isset($class::$sortable)
                    && in_array($sortField, $class::$sortable)
                ) {
                    $sortType = (strtoupper($sortType) == 'DESC') ? 'DESC' : 'ASC';

                    $tmp_model = new $class();
                    $scope_method = 'scopeOrderBy' . implode(array_map('ucfirst', $sort));
                    if (method_exists($tmp_model, $scope_method)) {
                        $method = 'orderBy' . implode(array_map('ucfirst', $sort));
                        $query = $query->{$method}($column, $sortType);
                    }
                }
            }
        }


        return $query;
    }
}
