<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

/**
 * @link https://www.advancedcustomfields.com/resources/file/
 */
class File extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        /*
         * file by id is unsupported, use array or url instead, array for best results.
         */
        if ('id' === $field_object['return_format']) {
            return false;
        }

        $file = self::icefield($field_name, $post_id);

        if (is_array($file)) {

            if ('video' === $file['type']) {
                $content = $this->getVideo($file);

                return $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
            }

            $wrapped = "<a class='{$field_classes}' href='{$file['url']}'>{$file['title']}</a>";
        } else {
            $wrapped = "<a class='{$field_classes}' href='{$file}'>{$file}</a>";
        }

        return $wrapped;
    }

    /*
     * Opinionated: assumes that a video added by file is for muted autoplay.
     */
    public function getVideo($file): string
    {
        $content = '';

        if (is_array($file)) {
            $content = "<video loop muted autoplay playsinline><source src='{$file['url']}' type='video/mp4' /></video>";
        }

        return $content;
    }

}
