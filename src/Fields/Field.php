<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Field
{
    /**
     * @param mixed    $field_object  The ACF Field object
     * @param string   $tag           The HTML tag to use
     * @param mixed    $post_id       indicates if cvurrent post or options page
     * @param string   $field_classes prepared CSS classes
     * @param string   $base_class    Base Class name for use on children
     * @param Icenberg $icenberg      The icenberg instance
     *
     * @return string
     */
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        return false;
    }

    public function wrap(string $field_name, string $tag, mixed $post_id, string $field_classes, ?string $content = null): string
    {
        if (!$content) {
            $content = self::icefield($field_name, $post_id);
        }

        return "<{$tag} class='{$field_classes}'>{$content}</{$tag}>";
    }

    public function listWrap(string $field_name, string $tag, mixed $post_id, string $field_classes, ?array $content = []): string
    {
        $list = '';

        if (!$content) {
            $content = self::icefield($field_name, $post_id);
        }

        foreach ($content as $item) {
            $list .= "<li>{$item}</li>";
        }

        return "<ul class='{$field_classes}'>{$list}</ul>";
    }

    /** @noinspection PhpUndefinedFunctionInspection */
    public static function icefield(string $field_name, mixed $post_id = false): mixed
    {
        if (!get_sub_field($field_name) && !get_field($field_name, $post_id)) {
            return false;
        }

        $field = false;

        if (get_sub_field($field_name)) {
            $field = get_sub_field($field_name);
        } elseif (get_field($field_name, $post_id)) {
            $field = get_field($field_name, $post_id);
        }

        return $field;
    }

    public function postLink($wp_post, $base_class): bool|string
    {
        if (!$wp_post) {
            return false;
        }

        $permalink = esc_url(get_the_permalink($wp_post));
        $title = get_the_title($wp_post);

        return "<a class='{$base_class}__link' href='{$permalink}'>{$title}</a>";
    }
}
