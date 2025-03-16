<?php

namespace MVRK\Icenberg\Commands;

use WP_CLI;
use WP_CLI_Command;

class Block
{
    /**
     * Creates a new block with PHP and SCSS files
     *
     * @param string $block_name
     * @param array $args
     * @return void
     */
    public static function create($block_name, $args)
    {
        $css_classes = self::generateCssClass($block_name, $args);
        $php_stub = self::generatePhpStub($args);
        $directories = self::createDirectories($block_name);

        self::writeFile($directories['file_path'], $php_stub);

        WP_CLI::success("New block created at {$directories['file_path']}");
    }

    /**
     * Generates the CSS classes for the block
     *
     * @param string $block_name
     * @param array $args
     * @return string
     */
    public static function generateCssClass($block_name, $args)
    {
        $css_name = str_replace('_', '-', $block_name);
        $classes = ".block--{$css_name} {" . PHP_EOL;
        $classes .= ".section__inner {}" . PHP_EOL;
        $classes .= ".wrapper {}" . PHP_EOL;

        foreach ($args as $arg) {
            $class = "&__" . str_replace('_', '-', $arg) . "{}" . PHP_EOL;
            $classes .= $class;
        }

        $classes .= '}' . PHP_EOL;
        return $classes;
    }

    /**
     * Generates the PHP stub for the block
     *
     * @param array $args
     * @return string
     */
    public static function generatePhpStub($args)
    {
        // Grab our stub files
        $stubs_directory = dirname(__DIR__, 2) . '/stubs';
        $php_stub = file_get_contents($stubs_directory . "/block.php.stub", true);

        // Append the icenberg elements onto the stub for each specified field
        $fields = "";
        foreach ($args as $arg) {
            $element = "'" . $arg . "'";
            $fields .= '$icenberg->get_element(' . $element . ');' . PHP_EOL;
        }

        return $php_stub . $fields;
    }

    /**
     * Checks if the directories exist, and creates them if not
     *
     * @param string $block_name
     * @return array
     */
    public static function createDirectories($block_name)
    {
        $blocks_dir = Bootstrap::getBlocksDirectory();
        $single_dir = $blocks_dir . "/{$block_name}";
        $file_path = $single_dir . "/{$block_name}.php";

        if (!is_dir($blocks_dir)) {
            mkdir($blocks_dir, 0755, true);
        }

        if (!is_dir($single_dir)) {
            mkdir($single_dir, 0755, true);
        }

        return [
            'blocks_dir' => $blocks_dir,
            'single_dir' => $single_dir,
            'file_path' => $file_path,
        ];
    }

    /**
     * Writes the PHP file to the specified location
     *
     * @param string $file_path
     * @param string $content
     * @return void
     */
    public static function writeFile($file_path, $content)
    {
        file_put_contents($file_path, $content);
        chmod($file_path, 0644);
    }
}
