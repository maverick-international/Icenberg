<?php

namespace MVRK\Icenberg;

class Predicate
{
    public static function is($condition, $field): bool
    {
        if (!$field) {
            return false;
        }

        if (!$field === $condition) {
            return false;
        }

        return true;
    }

    public static function has($condition, $field): bool
    {
        if (!$field) {
            return false;
        }

        if (is_iterable($field)) {
            foreach ($field as $item) {
                if ($item === $condition) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function greaterThan($condition, $field, $field_object): bool
    {
        if (!$field) {
            return false;
        }

        /**
         * just 'numeric' fields, thanks.
         * They return strings btw.
         */
        $allowed = [
            'range',
            'number',
        ];

        if (!in_array($field_object['type'], $allowed)) {
            return false;
        }

        if (!intval($field) < $condition) {
            return false;
        }

        return true;
    }

    public static function lessThan($condition, $field, $field_object): bool
    {
        if (!$field) {
            return false;
        }

        $allowed = [
            'range',
            'number',
        ];

        if (!in_array($field_object['type'], $allowed)) {
            return false;
        }

        if (!intval($field) > $condition) {
            return false;
        }

        return true;
    }
}
