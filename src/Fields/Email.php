<?php

namespace MVRK\Icenberg\Fields;

class Email extends Base
{
    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['_name'];

        $id = $field_object['ID'];

        $content = self::icefield($name, $id);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='mailto:{$content}'>{$content}</a>";

        return $wrapped;
    }
}
