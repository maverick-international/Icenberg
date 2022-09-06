<?php

namespace MVRK\Icenberg\Fields;

class Url extends Base
{
    /**
     * Scaffold a text field
     *
     * @param object $field
     * @param string $layout the current row layout
     * @return string
     */
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $content = get_sub_field($name);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$content}'>{$content}</a>";

        return $wrapped;
    }
}
