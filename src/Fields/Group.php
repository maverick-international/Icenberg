<?php

namespace MVK\Icenberg\Fields;

class Group extends Base
{
    public function getElement($field_object, $icenberg)
    {
        // dd($field_object);

        $name = $field_object['_name'];

        $sub_fields = [];

        foreach ($field_object['value'] as $key => $value) {

            array_push($sub_fields, $key);
        }

        $innards = "";

        if (have_rows($name)) :
            while (have_rows($name)) : the_row();

                foreach ($sub_fields as $sub_field) {
                    $innards .= $icenberg->get_element($sub_field);
                }

            endwhile;

        endif;

        $class = "block--{$icenberg->layout}__{$this->unSnake($name)}";
        $group = "<div class='{$class}'>{$innards}</div>";

        return $group;
    }
}
