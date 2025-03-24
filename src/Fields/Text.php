<?php

namespace MVRK\Icenberg\Fields;

class Text extends Base
{
    /**
     * Scaffold a text field
     *
     * @param object $field_object
     * @param string $layout the current row layout
     * @param string $tag
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
