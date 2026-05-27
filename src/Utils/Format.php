<?php

namespace MVRK\Icenberg\Utils;

class Format
{
    public static function kebabCase(string $string): string
    {
        // second regex preserves double underscores so that in nested contexts
        // like groups and repeaters the elements aren't converted to modifiers!
        $result = preg_replace('/([a-z])([A-Z])/', '$1-$2', $string);
        $result = preg_replace('/(?<!_)_(?!_)/', '-', $result);

        return strtolower($result);
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
