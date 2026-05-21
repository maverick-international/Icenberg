<?php

namespace MVRK\Icenberg\Utils;

class Format
{
    public static function kebabCase(string $string): string
    {
        $result = preg_replace('/([a-z])([A-Z])/', '$1-$2', $string);

        return strtolower(str_replace('_', '-', $result));
    }

    public static function pascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    public static function snakeCase(string $string): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

}
