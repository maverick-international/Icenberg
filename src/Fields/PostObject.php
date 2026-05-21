<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class PostObject extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        $values = $field_object['value'] ?? null;

        if (!is_array($values)) {
            $values = [$values];
        }

        $content = [];

        if ($values) {
            foreach ($values as $value) {
                $content[] = "{$this->postLink($value, $base_class)}>";
            }
        }

        return $this->listWrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
