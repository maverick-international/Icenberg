<?php

namespace MVRK\Icenberg\Fields;

class Select extends Base
{
    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field_object['_name'];

        $content = self::icefield($name, $post_id);

        if (is_iterable($content)) {
            $wrapped = $this->listWrap($content, $name, $icenberg, $modifiers);
        } else {
            $wrapped = $this->wrap($content, $name, $icenberg, $tag, $modifiers);
        }

        return $wrapped;
    }
}
