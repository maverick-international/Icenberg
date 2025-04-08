<?php

namespace MVRK\Icenberg\Fields;

class Link extends Base
{
    public function getElement($field, $icenberg, $tag, $post_id)
    {
        $name = $field['_name'];

        $link = self::icefield($name, $post_id);

        if (is_array($link)) {
            $wrapped = "<a class='button {$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}' href='{$link['url']}' target='{$link['target']}'>{$link['title']}</a>";
        } else {
            $wrapped = "<a class='button {$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}' href='{$link}'>{$link}</a>";
        }

        return $wrapped;
    }
}
