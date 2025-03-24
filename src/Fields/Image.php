<?php

namespace MVRK\Icenberg\Fields;

class Image extends Base
{
    /**
     * Scaffolds an image field
     *
     * @param object $field_object
     * @param string $layout
     * @return string
     */
    public function getElement($field_object, $layout, $tag, $options)
    {
        $name = $field_object['_name'];

        $image = self::icefield($name, $options);

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
