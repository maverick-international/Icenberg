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

        $base_class = "{$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($base_class, $modifiers);

        $class = "buttons {$base_class} {$modifier_classes}";
        $group = "<div class='{$class}'>{$innards}</div>";

        return $group;
    }
}
