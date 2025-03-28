<?php

namespace MVRK\Icenberg\Commands;

use WP_CLI;
use WP_CLI_Command;
use MVRK\Icenberg\Config\Config;

/**
 * Bootstraps our commands.
 * @disregard 1009
 */
class Bootstrap extends WP_CLI_Command
{
    protected static $theme_directory;

    protected static $blocks_directory;

    protected static $block_directory_name;

    protected static $sass_directory;

    protected static $sass_directory_name;

    public static function setup()
    {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

        Config::load();

        static::$block_directory_name = Config::get('block_directory_name') ?? 'blocks';

        /** @disregard P1010 */
        static::$blocks_directory = get_template_directory() . "/" . static::$block_directory_name;

        /** @disregard P1010 */
        static::$theme_directory = get_template_directory();

        static::$sass_directory_name =  Config::get('sass_directory') ?? 'src/sass/blocks';

        /** @disregard P1011 */
        static::$sass_directory = dirname(ABSPATH) . '/' . static::$sass_directory_name;

        /** @disregard P1009 */
        WP_CLI::add_command('icenberg', 'MVRK\\Icenberg\\Commands\\Cli');
    }

    public static function getThemeDirectory()
    {
        return static::$theme_directory;
    }

    public static function getBlocksDirectory()
    {
        return static::$blocks_directory;
    }

    public static function getSassDirectory()
    {
        return static::$sass_directory;
    }
}
