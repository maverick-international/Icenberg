<?php

namespace MVRK\Icenberg\Fields;

class Relationship extends Base
{
    /**
     * Relationships can work in icenberg!
     *
     */
    public function getElement($field_object, $icenberg, $tag, $post_id = false)
    {
        $name = $field_object['name'];

        $values = $field_object['value'] ?? null;

        $content = '';

        if ($values) {
            foreach ($values as $value) {
                $content .= $this->postLink($value, $name, $icenberg->layout, $tag);
            }
        }

        return $content;
    }
}
