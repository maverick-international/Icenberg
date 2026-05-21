<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Url extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        return "<a class='{$field_classes}' href='{$content}'>{$content}</a>";
    }
}
