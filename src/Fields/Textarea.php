<?php

namespace MVRK\Icenberg\Fields;

class Textarea extends Base
{
    /**
     * Scaffold a text field
     *
     * @param object $field
     * @param string $layout the current row layout
     * @return string
     */
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = get_sub_field($name);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
