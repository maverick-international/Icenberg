<?php

namespace MVRK\Icenberg\Fields;

class Select extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = self::icefield($name);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($content, $name, $layout);
        } else {
            $wrapped = $this->wrap($content, $name, $layout, $tag);
        }

        return $wrapped;
    }
}
