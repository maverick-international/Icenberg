<?php

namespace MVRK\Icenberg;

use MVRK\Icenberg\Utils\CSS;
use MVRK\Icenberg\Utils\Format;

class Enclose
{
    public static function get($class, $layout, $elements = [], $tag = 'div', $attrs = [], $modifiers = []): string
    {
        $layout = Format::kebabCase($layout);
        $base_class = $layout;

        if ($class) {
            $base_class .= "__{$class}";
        }

        $additional_classes = '';
        $additional_attrs = '';

        if (count($attrs)) {
            if (isset($attrs['class'])) { // handle classes separately as we already have a class attribute
                $additional_classes = $attrs['class'];
                unset($attrs['class']);
            }

            $additional_attrs = implode(' ', array_map(function ($key, $val) {
                return "{$key}='{$val}'";
            }, array_keys($attrs), array_values($attrs)));
        }

        $modifier_classes = CSS::generateModifierClasses($base_class, $modifiers);
        $classes_string = CSS::implodeClasses($base_class, $modifier_classes, $additional_classes);

        return "<{$tag} class='{$classes_string}' {$additional_attrs}>" . implode($elements) . "</{$tag}>";
    }
}
