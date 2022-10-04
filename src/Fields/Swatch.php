<?php

namespace MVRK\Icenberg\Fields;

class Swatch extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = get_sub_field($name);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($content, $name, $layout);
        } else {
            $wrapped = $this->wrap($content, $name, $layout, $tag);
        }

        return $wrapped;
    }
}
