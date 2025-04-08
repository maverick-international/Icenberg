<?php

namespace MVRK\Icenberg\Fields;

class Oembed extends Base
{
    public function getElement($field, $icenberg, $tag, $post_id)
    {
        $name = $field['_name'];

        $content = self::icefield($name, $post_id);

        $wrapped = $this->wrap($content, $name, $icenberg, $tag);

        return $wrapped;
    }
}
