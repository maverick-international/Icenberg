<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Fields\Button;
use MVRK\Icenberg\Icenberg;

class Buttons extends Base
{
    public function getElement($field_object, $icenberg, $modifiers = [])
    {
        $name = $field_object['_name'];

        $innards = "";

        if (have_rows($name)) :
            while (have_rows($name)) : the_row();

                $button = (new Button)->prepareButton('button');

                $innards .= $button;

            endwhile;
        endif;

        $base_class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($base_class, $modifiers);
        $classes_string = Icenberg::implodeClasses('buttons', $base_class, $modifier_classes);

        $group = "<div class='{$classes_string}'>{$innards}</div>";

        return $group;
    }
}
