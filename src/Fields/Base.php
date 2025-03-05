<?php

namespace MVRK\Icenberg\Fields;

class Base
{

    public function wrap($content, $classes, $layout, $tag)
    {
        return "<{$tag} class='block--{$this->unSnake($layout)}__{$this->unSnake($classes)}'>{$content}</{$tag}>";
    }


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
        return  str_replace('_', '-', $text);
    }

    public function unSpace($string)
    {
        return str_replace([' ', '-'], '_', $string);
    }

    public static function icefield($field_name)
    {
        if (!get_sub_field($field_name) && !get_field($field_name)) {
            return;
        }

        if (get_sub_field($field_name)) {
            $field = get_sub_field($field_name);
        } else if (get_field($field_name)) {
            $field = get_field($field_name);
        }

        return $field;
    }
}
