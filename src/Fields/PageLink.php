<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class PageLink extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];
        $links = self::icefield($field_name, $post_id);
        $content = [];

        if (is_string($links)) {
            $links = [$links];
        }

        foreach ($links as $link) {
            $content[] = "<a class='{$base_class}__item' href='{$link}'>{$link}</a>";
        }

        return $this->listWrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
