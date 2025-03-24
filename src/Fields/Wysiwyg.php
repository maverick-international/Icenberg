<?php

namespace MVRK\Icenberg\Fields;

class Wysiwyg extends Base
{
    /**
     * Scaffold a WYSIWYG field
     *
     * @param object $field_object
     * @param string $layout the current row
     * @return string
     */
    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $options);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
