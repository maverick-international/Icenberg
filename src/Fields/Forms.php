<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

/**
 * Used in conjunction with Advanced Custom Fields: Gravity Forms Add-on
 * @link https://github.com/SayHelloGmbH/acf-gravityforms-add-on/
 */
class Forms extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        // depends on gravity forms existing
        if (!class_exists('GFAPI')) {
            return '';
        }

        $field_name = $field_object['_name'];

        $form = $field_object['value'];

        $content = gravity_form($form['id'], $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = 0, $echo = false);

        return $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
