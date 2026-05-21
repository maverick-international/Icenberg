<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class PostObject extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];

        $posts = $field_object['value'] ?? null;

        if (!is_array($posts)) {
            $posts = [$posts];
        }

        $content = [];

        if ($posts) {
            foreach ($posts as $post) {
                setup_postdata($post);
                $content[] = "{$this->postLink($post, $base_class)}";
            }
            wp_reset_postdata();
        }
        
        return $this->listWrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
