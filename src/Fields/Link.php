<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Link extends Base
{
    public function getElement($field, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field['_name'];

        $link = self::icefield($name, $post_id);

        $base_class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($base_class, $modifiers);

        if (is_array($link)) {
            $wrapped = "<a class='button {$base_class} {$modifier_classes}' href='{$link['url']}' target='{$link['target']}'>{$link['title']}</a>";
        } else {
            $wrapped = "<a class='button {$base_class} {$modifier_classes}' href='{$link}'>{$link}</a>";
        }

        return $wrapped;
    }
}
