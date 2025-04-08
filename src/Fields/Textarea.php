<?php

namespace MVRK\Icenberg\Fields;

class Textarea extends Base
{
    /**
     * Scaffold a text field
     *
     * @param object $field_object
     * @param string $layout the current row layout
     * @return string
     */
    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        $wrapped = $this->wrap($content, $name, $icenberg, $tag);

        return $wrapped;
    }
}
