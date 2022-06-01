<?php

namespace MVK\Icenberg\Fields;

class Link extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $link = get_sub_field($name);

        if (is_array($link)) {
            $wrapped = "<a class='button block--{$layout}__{$this->unSnake($name)}' href='{$link['url']}' target='{$link['target']}'>{$link['title']}</a>";
        } else {
            $wrapped = "<a class='button block--{$layout}__{$this->unSnake($name)}'  href='{$link}'>{$link}</a>";
        }

        return $wrapped;
    }
}
