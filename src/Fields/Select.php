<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Select extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        $content = self::icefield($field_name, $post_id);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($field_name, $tag, $post_id, $field_classes, $content);
        } else {
            $wrapped = $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
        }

        return $wrapped;
    }
}
