<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Image extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        $image = self::icefield($field_name, $post_id);

        // handle each possible format of image uses array, url or id
        if (is_array($image)) {
            $content = wp_get_attachment_image($image['id'], 'full');
        } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
            $content = "<img src='{$image}' alt=''>";
        } else {
            $content = wp_get_attachment_image($image, 'full');
        }

        return $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
