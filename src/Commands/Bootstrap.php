<?php

namespace MVRK\Icenberg\Commands;

use MVRK\Icenberg\Config\Config;
use WP_CLI;
use WP_CLI_Command;

class Bootstrap extends WP_CLI_Command
{
    protected static string $theme_directory;
    protected static string $blocks_directory;
    protected static string $block_directory_name;
    protected static string $sass_directory;
    protected static string $sass_directory_name;
    protected static string $block_prefix;

    public static function setup(): void
    {
        // look in the root for the vendor folder or else look in the theme root for non-maverick users
        $autoload_paths = [
            dirname(ABSPATH) . '/vendor/autoload.php',
            ABSPATH . 'vendor/autoload.php',
        ];

        $found = false;

        foreach ($autoload_paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                $found = true;
                break;
            }
        }

        if (!$found) {
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::error('Could not find vendor/autoload.php in either ABSPATH or its parent directory.');
            } else {
                exit('Autoloader not found. Please run `composer install`.');
            }
        }

        Config::load();

        static::$block_directory_name = Config::get('block_directory_name') ?? 'blocks';
        static::$block_prefix = Config::get('block_editor_prefix') ?? 'block';
        static::$blocks_directory = get_template_directory() . "/" . static::$block_directory_name;
        static::$theme_directory = get_template_directory();
        static::$sass_directory_name = Config::get('sass_directory') ?? 'src/sass/blocks';
        static::$sass_directory = dirname(ABSPATH) . '/' . static::$sass_directory_name;

        WP_CLI::add_command('icenberg', 'MVRK\\Icenberg\\Commands\\Cli');
    }

    public static function getThemeDirectory(): string
    {
        return static::$theme_directory;
    }

    public static function getBlocksDirectory(): string
    {
        return static::$blocks_directory;
    }

    public static function getSassDirectory(): string
    {
        return static::$sass_directory;
    }

    public static function getBlockPrefix(): string
    {
        return static::$block_prefix;
    }

}
