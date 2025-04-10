<?php

namespace MVRK\Icenberg\Fields;

class Image extends Base
{

    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $name = $field_object['_name'];

        $image = self::icefield($name, $post_id);

        //handle each possible format of image uses array, url or id
        if (is_array($image)) {
            $content = wp_get_attachment_image($image['id'], 'full');
        } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
            $content = "<img src='{$image}' alt=''>";
        } else {
            $content =  wp_get_attachment_image($image, 'full');
        }

        $wrapped = $this->wrap($content, $name, $icenberg, $tag, $modifiers);

        return $wrapped;
    }
}
