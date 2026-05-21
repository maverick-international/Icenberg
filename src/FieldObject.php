<?php

namespace MVRK\Icenberg;

class FieldObject
{
    /** @noinspection PhpUndefinedFunctionInspection */
    public static function get(string $field_name, mixed $post_id = false)
    {
        $field_object = null;

        if (strpos($field_name, ',')) {
            $field_name = strstr($field_name, ',', true);
            $post_id = 'options';
        }

        if (!get_sub_field($field_name) && !get_field($field_name, $post_id)) {
            return null;
        }

        if (get_sub_field($field_name)) {
            $field_object = get_sub_field_object($field_name);
        } elseif (get_field($field_name, $post_id)) {
            $field_object = get_field_object($field_name, $post_id);
        }

        return $field_object;
    }
}
