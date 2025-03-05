<?php

namespace MVRK\Icenberg\Fields;

class PageLink extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $link = self::icefield($name);

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$link}'>{$link}</a>";

        return $wrapped;
    }
}
