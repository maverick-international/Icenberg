<?php

namespace MVRK\Icenberg\Fields;

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
    public function wrap($content, $classes, $layout, $tag)
    {
        return "<{$tag} class='block--{$this->unSnake($layout)}__{$this->unSnake($classes)}'>{$content}</{$tag}>";
    }

    /**
     * Wrap content in a html list
     *
     * @param mixed $content
     * @param mixed $classes
     * @param mixed $layout
     * @return string
     */
    public function listWrap($content, $classes, $layout)
    {
        $list = "";

        foreach ($content as $item) {
            $list .= "<li>{$item}</li>";
        }

        return "<ul class='block--{$this->unSnake($layout)}__{$this->unSnake($classes)}'>{$list}</ul>";
    }

    public function unSnake($text)
    {
        return str_replace('_', '-', $text);
    }

    public function unSpace($string)
    {
        return str_replace([' ', '-'], '_', $string);
    }

    /**
     * Prepare the field when retrieved from the field object
     *
     * @param mixed $field_name
     * @param mixed $id
     * @return mixed
     */
    public static function icefield($field_name, $options = null)
    {
        if (!get_sub_field($field_name) && !get_field($field_name, $options)) {
            return;
        }

        if (get_sub_field($field_name)) {
            $field = get_sub_field($field_name);
        } else if (get_field($field_name, $options)) {
            $field = get_field($field_name, $options);
        }

        return $field;
    }


    protected static function wrapInTag($content, $item_name, $class, $tag = 'span')
    {
        return "<{$tag} class='{$class}__{$item_name}'>{$content}</{$tag}>";
    }

    public function preview($wp_post, $name, $layout, $tag)
    {
        if (!$wp_post) {
            return false;
        }

        $permalink = esc_url(get_the_permalink($wp_post));
        $title = get_the_title($wp_post);
        $excerpt = get_the_excerpt($wp_post);
        $thumbnail = get_the_post_thumbnail($wp_post);
        $date = get_the_date('j M Y', $wp_post);
        $class = "preview--{$this->unSnake($layout)}__{$this->unSnake($name)}";
        $linked_title = "<a href='{$permalink}'>{$title}</a>";
        $link = "<a href='{$permalink}'>Read More</a>";

        $content = "<{$tag} class='{$class}'>";
        $content .= self::wrapInTag($date, 'date', $class, 'div');
        $content .= self::wrapInTag($thumbnail, 'thumbnail', $class, "div");
        $content .= self::wrapInTag($linked_title, 'title', $class, 'h4');
        $content .= self::wrapInTag($excerpt, 'excerpt', $class, 'div');
        $content .= self::wrapInTag($link, 'link', $class, "div");
        $content .= "</{$tag}>";

        return $content;
    }
}
