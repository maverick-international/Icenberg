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

        $class = "block--{$this->unSnake($layout)}__{$this->unSnake($name)}";

        foreach ($links as $link) {
            $wrapped .= "<li><a class='{$class}__item' href='{$link}'>{$link}</a></li>";
        }

        $wrapped = sprintf('<ul %s>%s</ul>', $class, $wrapped);

        return $wrapped;
    }
}
