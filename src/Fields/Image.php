<?php

namespace MVRK\Icenberg\Fields;

class Image extends Base
{
    /**
     * Scaffolds an image field
     *
     * @param object $field
     * @param string $layout
     * @return string
     */
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $image = self::icefield($name);

        //handle each possible format of image uses array, url or id
        if (is_array($image)) {
            $content = wp_get_attachment_image($image['id'], 'full');
        } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
            $content = "<img src='{$image}' alt=''>";
        } else {
            $content =  wp_get_attachment_image($image, 'full');
        }

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }
}
