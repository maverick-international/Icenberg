<?php

namespace MVK\Icenberg\Fields;

class Repeater extends Base
{
    public function getElement($field_object, $icenberg)
    {

        $name = $field_object['_name'];

        $innards = "";

        $class = "block--{$icenberg->layout}__{$this->unSnake($name)}";


        if (have_rows($name)) :
            while (have_rows($name)) : the_row();

                $gizzards = '';

                foreach ($field_object['sub_fields']  as $sub_field) {
                    $gizzards .= $icenberg->get_element($sub_field['name']);
                }

                $innards .= "<div class='{$class}__item'>{$gizzards}</div>";

            endwhile;

        endif;

        $repeater = "<div class='{$class}'>{$innards}</div>";

        return $repeater;
    }
}
