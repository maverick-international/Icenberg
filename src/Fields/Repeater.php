<?php

namespace MVRK\Icenberg\Fields;

class Repeater extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        $name = $field_object['_name'];

        $innards = "";

        $class = "{$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}";

        if (have_rows($name, $post_id)) :
            while (have_rows($name, $post_id)) : the_row();

                $gizzards = '';

                foreach ($field_object['sub_fields'] as $sub_field) {
                    $gizzards .= $icenberg->get_element($sub_field['name']);
                }

                $innards .= "<div class='{$class}__item'>{$gizzards}</div>";

            endwhile;

        endif;

        $repeater = "<{$tag} class='{$class}'>{$innards}</{$tag}>";

        return $repeater;
    }
}
