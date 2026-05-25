<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class DateTimePicker extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        return $this->wrap($field_name, $tag, $post_id, $field_classes);
    }
}
