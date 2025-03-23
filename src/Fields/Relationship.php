<?php

namespace MVRK\Icenberg\Fields;

class Relationship extends Base
{
    /**
     * Relationships can't work in icenberg
     * becasue it takes us out of the_row(),
     * (its not you, its me).
     *
     */
    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['name'];

        $values = $field_object['value'] ?? null;

        $content = '';

        if ($values) {
            foreach ($values as $value) {
                $content .= $this->preview($value, $name, $layout, $tag);
            }
        }

        return $content;
    }
}
