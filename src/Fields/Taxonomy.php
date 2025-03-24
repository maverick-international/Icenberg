<?php

namespace MVRK\Icenberg\Fields;

class Taxonomy extends Base
{
    public function getElement($field_object, $layout, $tag, $options)
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
