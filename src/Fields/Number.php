<?php

namespace MVRK\Icenberg\Fields;

class Number extends Base
{

    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $options);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
