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
}
