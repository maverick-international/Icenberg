<?php

namespace MVRK\Icenberg\Fields;

class Oembed extends Base
{
    public function getElement($field, $layout, $tag, $options)
    {
        $name = $field['_name'];

        $content = self::icefield($name, $options);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
