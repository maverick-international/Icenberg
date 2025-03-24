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
    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $options);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$content}'>{$content}</a>";

        return $wrapped;
    }
}
