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
    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        $wrapped = $this->wrap($content, $name, $icenberg, $tag, $modifiers);

        return $wrapped;
    }
}
