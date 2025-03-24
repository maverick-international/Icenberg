<?php

namespace MVRK\Icenberg\Fields;

class PageLink extends Base
{
    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $links = self::icefield($name, $options);

        if (is_string($links)) {
            $links = [$links];
        }

        $wrapped = '';

        foreach ($links as $link) {
            $wrapped .= "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$link}'>{$link}</a>";
        }

        return $wrapped;
    }
}
