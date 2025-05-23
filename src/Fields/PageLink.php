<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class PageLink extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field_object['_name'];

        $links = self::icefield($name, $post_id);

        if (is_string($links)) {
            $links = [$links];
        }

        $wrapped = '';

        $class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($class, $modifiers);
        $classes_string = Icenberg::implodeClasses($class, $modifier_classes);

        foreach ($links as $link) {
            $wrapped .= "<li><a class='{$class}__item' href='{$link}'>{$link}</a></li>";
        }

        $wrapped = sprintf('<ul class="%s">%s</ul>', $classes_string, $wrapped);

        return $wrapped;
    }
}
