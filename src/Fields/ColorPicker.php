<?php

namespace MVRK\Icenberg\Fields;

class ColorPicker extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = get_sub_field($name);

        $wrapped = $this->listWrap($content, $name, $layout);

        return $wrapped;
    }
}
