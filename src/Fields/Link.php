<?php

namespace MVRK\Icenberg\Fields;

class Link extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $link = self::icefield($name);

        if (is_array($link)) {
            $wrapped = "<a class='button block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$link['url']}' target='{$link['target']}'>{$link['title']}</a>";
        } else {
            $wrapped = "<a class='button block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$link}'>{$link}</a>";
        }

        return $wrapped;
    }
}
