<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Repeater extends Field
{
    /** @noinspection PhpUndefinedFunctionInspection */
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];
        $content = '';

        if (have_rows($field_name, $post_id)) :
            while (have_rows($field_name, $post_id)) : the_row();
                $repeater_item = '';

                foreach ($field_object['sub_fields'] as $sub_field) {
                    // pass the existing classes down the line for better BEM
                    $ice = new Icenberg($icenberg->layout . "__" . $field_name, $icenberg->prefix);
                    $repeater_item .= $ice->get_element($sub_field['name']);
                }

                $content .= "<div class='{$base_class}__item'>{$repeater_item}</div>";

            endwhile;
        endif;

        return "<{$tag} class='{$field_classes}'>{$content}</{$tag}>";
    }
}
