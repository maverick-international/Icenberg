<?php

namespace MVRK\Icenberg\Config;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Config
{
    protected static array $config = [];

    /**
     * Load the YAML file and parse it into the static $config property.
     */
    public static function load(string $file = null): void
    {
        $file = $file ?? dirname(ABSPATH) . '/icenberg.yaml';

        if (file_exists($file)) {
            try {
                self::$config = Yaml::parseFile($file);
            } catch (ParseException $e) {
                echo "Unable to parse the YAML file: " . $e->getMessage();
            }
        } else {
            echo "Config file {$file} does not exist. Using default configuration.";
        }
    }

    public static function get($key): mixed
    {
        return self::$config[$key] ?? null;
    }

    public static function getAll(): array
    {
        return self::$config;
    }
}
