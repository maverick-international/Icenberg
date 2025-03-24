<?php

namespace MVRK\Icenberg\Fields;

class ColorPicker extends Base
{
    public function getElement($field_object, $layout, $tag)
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
