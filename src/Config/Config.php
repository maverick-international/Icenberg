<?php

namespace MVRK\Icenberg\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
    protected static $config = [];

    /**
     * Load the YAML file and parse it into the static $config property.
     *
     * @param string $file
     * @return void
     */
    public static function load($file = null)
    {
        /** @disregard P1011 */
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

    /**
     * Get the value of a specific config key.
     *
     * @param string $key The config key to retrieve.
     * @return mixed|null
     */
    public static function get($key)
    {
        return self::$config[$key] ?? null;
    }

    /**
     * Get all configurations.
     *
     * @return array The entire configuration array.
     */
    public static function getAll()
    {
        return self::$config;
    }
}
