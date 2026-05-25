<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class IconPicker extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        $field = self::icefield($field_name, $post_id);

        if (is_string($field)) {
            $field_classes .= $field ? ' ' . $field : '';
            // if it's a url then we should display an image, otherwise add it to the classes
            if (filter_var($field, FILTER_VALIDATE_URL)) {
                $content = "<img src='{$field}' alt='' title='icon'/>";
            } else {
                $content = ' '; // important whitespace
            }
            return $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
        }

        /**
         * Available types are 'dashicons', 'media_library, 'url'. For dashicons to work you;'ll need to enqueue their css.
         * Customisation is possible so icomoon is pre-handled, but you'll have to implement it yourself.
         * @link https://www.advancedcustomfields.com/resources/icon-picker/
         */
        $icon_class = match ($field['type']) {
            'dashicons' => $field['value'], // a string in dashicons case, an array otherwise,
            'media_library' => 'media-library-icon',
            'url', 'default' => 'custom-icon',
            'icomoon' => "icon {$field['value']}"
        };

        $field_classes .= $icon_class ? ' ' . $icon_class : '';

        $content = match ($field['type']) {
            'dashicons', 'icomoon', 'default' => ' ',
            'media_library', 'url' => $field['value']['url'] ?? '',
        };

        return $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
