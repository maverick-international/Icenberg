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

        $class = "{$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($class, $modifiers);
        $group = "<{$tag} class='{$class} {$modifier_classes}'>{$innards}</{$tag}>";

        return $group;
    }
}
