<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Group extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field_object['_name'];

        $sub_fields = [];

        foreach ($field_object['value'] as $key => $value) {

            array_push($sub_fields, $key);
        }

        $innards = "";

        if (have_rows($name, $post_id)) :
            while (have_rows($name, $post_id)) : the_row();

                foreach ($sub_fields as $sub_field) {
                    $innards .= $icenberg->get_element($sub_field);
                }

            endwhile;

        endif;

        $class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($class, $modifiers);
        $classes_string = Icenberg::implodeClasses($class, $modifier_classes);

        $group = "<{$tag} class='{$classes_string}'>{$innards}</{$tag}>";

        return $group;
    }
}
