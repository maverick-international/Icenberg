<?php

namespace MVRK\Icenberg\Fields;

class Select extends Base
{
    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['_name'];

        $id = $field_object['ID'];

        $content = self::icefield($name, $id);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($content, $name, $layout);
        } else {
            $wrapped = $this->wrap($content, $name, $layout, $tag);
        }

        return $wrapped;
    }
}
