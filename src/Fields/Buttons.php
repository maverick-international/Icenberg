<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Fields\Button;

class Buttons extends Base
{
    public function getElement($field_object, $layout)
    {
        $name = $field_object['_name'];

        $innards = "";

        if (have_rows($name)) :
            while (have_rows($name)) : the_row();

                $button = (new Button)->prepareButton('button');

                $innards .= $button;

            endwhile;
        endif;

        $class = "buttons block--{$this->unSnake($layout)}__{$this->unSnake($name)}";
        $group = "<div class='{$class}'>{$innards}</div>";

        return $group;
    }
}
