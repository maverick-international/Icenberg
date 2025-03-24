<?php

namespace MVRK\Icenberg\Fields;

class Number extends Base
{

    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['_name'];

        $id = $field_object['ID'];

        $content = self::icefield($name, $id);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
