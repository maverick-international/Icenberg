<?php

namespace MVRK\Icenberg\Fields;

class Email extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = get_sub_field($name);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='mailto:{$content}'>{$content}</a>";

        return $wrapped;
    }
}
