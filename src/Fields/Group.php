<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Group extends Field
{
    /** @noinspection PhpUndefinedFunctionInspection */
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $name = $field_object['_name'];
        $sub_fields = [];
        $content = "";

        foreach ($field_object['value'] as $key => $value) {
            $sub_fields[] = $key;
        }
        
        if (have_rows($name, $post_id)) :
            while (have_rows($name, $post_id)) : the_row();

                foreach ($sub_fields as $sub_field) {
                    $content .= $icenberg->get_element($sub_field);
                }

            endwhile;
        endif;

        return "<{$tag} class='{$field_classes}'>{$content}</{$tag}>";
    }
}
