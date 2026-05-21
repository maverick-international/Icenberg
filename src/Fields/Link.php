<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

/**
 * @link https://www.advancedcustomfields.com/resources/link/
 */
class Link extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        if (is_array($content)) {
            $wrapped = "<a class='{$field_classes}' href='{$content['url']}' target='{$content['target']}'>{$content['title']}</a>";
        } else {
            $wrapped = "<a class='{$field_classes}' href='{$content}'>{$content}</a>";
        }

        return $wrapped;
    }
}
