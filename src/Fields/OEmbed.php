<?php

namespace MVRK\Icenberg\Fields;

class Oembed extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = self::icefield($name);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
