<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

class Base
{
    /**
     * Wrap the field in html
     *
     * @param mixed $content
     * @param mixed $classes
     * @param mixed $layout
     * @param mixed $tag
     * @return string
     */
    public function wrap($content, $classes, $icenberg, $tag, $modifiers = [])
    {
        $base_class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($classes)}";
        $modifier_classes = Icenberg::generateModifierClasses($base_class, $modifiers);
        $classes_string = Icenberg::implodeClasses($base_class, $modifier_classes);

        return "<{$tag} class='{$classes_string}'>{$content}</{$tag}>";
    }

    /**
     * Wrap iterable content in a html list
     *
     * @param mixed $content
     * @param mixed $classes
     * @param mixed $layout
     * @return string
     */
    public function listWrap($content, $classes, $icenberg, $modifiers = [])
    {
        $list = "";

        foreach ($content as $item) {
            $list .= "<li>{$item}</li>";
        }

        $base_class = "{$icenberg->prefix}{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($classes)}";
        $modifier_classes = Icenberg::generateModifierClasses($base_class, $modifiers);
        $classes_string = Icenberg::implodeClasses($base_class, $modifier_classes);

        return "<ul class='{$classes_string}'>{$list}</ul>";
    }

    /**
     * Prepare the field when retrieved from the field object
     *
     * @param mixed $field_name
     * @param mixed $post_id
     * @return mixed
     */
    public static function icefield($field_name, $post_id = false)
    {
        if (!get_sub_field($field_name) && !get_field($field_name, $post_id)) {
            return;
        }

        if (get_sub_field($field_name)) {
            $field = get_sub_field($field_name);
        } else if (get_field($field_name, $post_id)) {
            $field = get_field($field_name, $post_id);
        }

        return $field;
    }

    protected static function wrapInTag($content, $item_name, $class, $tag = 'span')
    {
        return "<{$tag} class='{$class}__{$item_name}'>{$content}</{$tag}>";
    }

    public function postLink($wp_post, $name, $icenberg, $tag, $modifiers = [])
    {
        if (!$wp_post) {
            return false;
        }

        $permalink = esc_url(get_the_permalink($wp_post));
        $title = get_the_title($wp_post);
        $class = "post-link--{$icenberg::unSnake($icenberg->layout)}__{$icenberg::unSnake($name)}";
        $modifier_classes = Icenberg::generateModifierClasses($class, $modifiers);
        $classes_string = Icenberg::implodeClasses($class, $modifier_classes);
        $content = "<a class='{$classes_string}' href='{$permalink}'>{$title}</a>";

        return $content;
    }
}
