<?php

namespace MVRK\Icenberg\Fields;

class Group extends Base
{
    public function getElement($field_object, $icenberg, $tag = 'div')
    {
        $name = $field_object['_name'];

        $sub_fields = [];

        foreach ($field_object['value'] as $key => $value) {

            array_push($sub_fields, $key);
        }

        $innards = "";

        $options = false;

        if ($field_object['ID'] === 0) {
            $options = 'options';
        }

        if (have_rows($name, $options)) :
            while (have_rows($name, $options)) : the_row();

                foreach ($sub_fields as $sub_field) {
                    $innards .= $icenberg->get_element($sub_field);
                }

            endwhile;

        endif;

        $class = "block--{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}";
        $group = "<{$tag} class='{$class}'>{$innards}</{$tag}>";

        return $group;
    }
}
