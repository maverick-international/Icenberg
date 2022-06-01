<?php

namespace MVRK\Icenberg\Fields;


class Gallery extends Base
{
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $gallery = get_sub_field($name);

        $images = '';

        foreach ($gallery as $image) {
            if (is_array($image)) {
                $content = wp_get_attachment_image($image['id'], 'full');
            } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
                $content = "<img src='{$image}' alt=''>";
            } else {
                $content = wp_get_attachment_image($image, 'full');
            }
            $images .= "<div class='block--{$layout}__image'>{$content}</div>";
        }

        $wrapped = $this->wrap($images, $name, $layout, $tag);

        return $wrapped;
    }
}
