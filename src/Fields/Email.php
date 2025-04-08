<?php

namespace MVRK\Icenberg\Fields;

class Email extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        $wrapped = "<a class='{$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}' href='mailto:{$content}'>{$content}</a>";

        return $wrapped;
    }
}
