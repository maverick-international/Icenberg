<?php

namespace MVRK\Icenberg\Fields;

class TrueFalse extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
