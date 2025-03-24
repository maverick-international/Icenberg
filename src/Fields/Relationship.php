<?php

namespace MVRK\Icenberg\Fields;

class Relationship extends Base
{
    /**
     * Relationships can work in icenberg!
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
