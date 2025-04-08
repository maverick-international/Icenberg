<?php

namespace MVRK\Icenberg\Fields;

class Range extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        $wrapped = $this->wrap($content, $name, $icenberg, $tag);

        return $wrapped;
    }
}
