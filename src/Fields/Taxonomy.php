<?php

namespace MVRK\Icenberg\Fields;

class Taxonomy extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
