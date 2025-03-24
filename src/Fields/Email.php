<?php

namespace MVRK\Icenberg\Fields;

class Email extends Base
{
    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $options);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='mailto:{$content}'>{$content}</a>";

        return $wrapped;
    }
}
