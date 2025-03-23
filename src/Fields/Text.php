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
    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['_name'];

        /**
         * we're passing the ID to identify if
         * this is an options field, as all options
         * field objects have an ID of 0
         */
        $id = $field_object['ID'];

        $content = self::icefield($name, $id);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
