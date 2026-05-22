<?php

namespace MVRK\Icenberg;

/**
 * Pulls a sub-field value directly from a group.
 */
class Member
{
    public static function get($target, $group, $post_id, $icenberg)
    {
        $group = FieldObject::get($group, $post_id);

        if (!$group) {
            return null;
        }

        if (!isset($group['value'])) {
            return null;
        }

        if (isset($group['value'][$target])) {
            return $group['value'][$target];
        }

        return null;
    }
}
