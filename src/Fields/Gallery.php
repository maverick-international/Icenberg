<?php

namespace MVRK\Icenberg\Fields;

class Gallery extends Base
{
    public function getElement($field, $icenberg, $tag, $post_id)
    {
        $name = $field['_name'];

        $gallery = self::icefield($name, $post_id);

        $images = '';

        foreach ($gallery as $image) {
            if (is_array($image)) {
                $content = wp_get_attachment_image($image['id'], 'full');
            } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
                $content = "<img src='{$image}' alt=''>";
            } else {
                $content = wp_get_attachment_image($image, 'full');
            }
            $images .= "<div class='{$icenberg->prefix}{$icenberg->layout}__image'>{$content}</div>";
        }

        $wrapped = $this->wrap($images, $name, $icenberg, $tag);

        return $wrapped;
    }
}
