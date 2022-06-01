<?php

namespace MVK\Icenberg\Fields;

class Wysiwyg extends Base
{
    /**
     * Scaffold a WYSIWYG field
     *
     * @param object $field
     * @param string $layout the current row
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
