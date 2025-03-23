<?php

namespace MVRK\Icenberg\Fields;

class Url extends Base
{
    /**
     * Scaffold a text field
     *
     * @param object $field_object
     * @param string $layout the current row layout
     * @return string
     */
    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['_name'];
        $id = $field_object['ID'];

        $content = self::icefield($name, $id);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$content}'>{$content}</a>";

        return $wrapped;
    }
}
