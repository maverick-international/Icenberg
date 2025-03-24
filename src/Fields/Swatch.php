<?php

namespace MVRK\Icenberg\Fields;

class Swatch extends Base
{
    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $options);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($content, $name, $layout);
        } else {
            $wrapped = $this->wrap($content, $name, $layout, $tag);
        }

        return $wrapped;
    }
}
