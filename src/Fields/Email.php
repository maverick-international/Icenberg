<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Email extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        $base_class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($base_class, $modifiers);

        $wrapped = "<a class='{$base_class} {$modifier_classes}' href='mailto:{$content}'>{$content}</a>";

        return $wrapped;
    }
}
