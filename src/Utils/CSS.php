<?php

namespace MVRK\Icenberg\Utils;

class CSS
{
    public static function generateModifierClasses(string $base_class, array $modifiers): string
    {
        $modifier_classes = '';

        if (count($modifiers)) {
            $modifier_classes = self::implodeClasses(...array_map(function ($key) use ($base_class, $modifiers) {
                $val = $modifiers[$key];

                if (is_string($key)) {
                    if ($val) {
                        return $base_class . "--" . Format::kebabCase($key);
                    }
                } else {
                    return $base_class . '--' . Format::kebabCase($val);
                }
            }, array_keys($modifiers)));
        }

        return $modifier_classes;
    }

    public static function implodeClasses(...$classes): string
    {
        return trim(implode(' ', $classes));
    }

    public static function fieldClasses(string $layout, string $field_name, string $prefix, array $modifiers = []): string
    {
        $base_class = self::generateBaseFieldClass($layout, $field_name, $prefix);
        $modifier_classes = self::generateModifierClasses($base_class, $modifiers);

        return self::implodeClasses($base_class, $modifier_classes);
    }

    public static function generateBaseFieldClass(string $layout, string $field_name, ?string $prefix = ''): string
    {
        $base_class = self::generateBaseClass($layout, $prefix);
        $field_name = Format::kebabCase($field_name);

        return "{$base_class}__{$field_name}";
    }

    public static function generateBaseClass(string $layout, ?string $prefix = ''): string
    {
        $layout = Format::kebabCase($layout);

        if ($prefix) {
            return "{$prefix}--{$layout}";
        }

        return $layout;
    }

}
