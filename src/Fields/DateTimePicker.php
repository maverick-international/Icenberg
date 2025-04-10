<?php

namespace MVRK\Icenberg\Fields;

class DateTimePicker extends Base
{
    public function getElement($field_object, $layout, $tag, $post_id, $modifiers = [])
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
