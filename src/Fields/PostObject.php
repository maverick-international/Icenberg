<?php

namespace MVRK\Icenberg\Fields;

class PostObject extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id = false, $modifiers = [])
    {
        $name = $field_object['_name'];

        $values = $field_object['value'] ?? null;

        if (!is_array($values)) {
            $values = [$values];
        }

        $content = '';

        if ($values) {
            foreach ($values as $value) {
                $content .= $this->postLink($value, $name, $icenberg, $tag, $modifiers);
            }
        }

        return $content;
    }
}
