<?php

namespace MVRK\Icenberg\Fields;

class Select extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($content, $name, $icenberg);
        } else {
            $wrapped = $this->wrap($content, $name, $icenberg, $tag);
        }

        return $wrapped;
    }
}
