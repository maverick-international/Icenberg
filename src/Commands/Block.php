<?php

namespace MVRK\Icenberg\Commands;

use WP_CLI;

class Block
{
    /**
     * Creates a new block with necessary files
     *
     * @param string $block_name
     * @param array $args
     * @return void
     */
    public static function create($block_name, $args)
    {
        $css_classes = self::generateCssClass($block_name, $args);
        $php_stub = self::generatePhpStub($args);
        $json_stub = self::generateJsonStub($block_name);
        $directories = self::createDirectories($block_name);

        self::writeFile($directories['php_file_path'], $php_stub);
        self::writeFile($directories['scss_file_path'], $css_classes);
        self::writeFile($directories['json_file_path'], $json_stub);

        self::createEmptyFieldGroup($block_name);

        /** @disregard P1009 */
        WP_CLI::success("New block created at {$directories['single_dir']}");
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
     * Creates an empty field group in ACF for the block
     *
     * @link https://make.wordpress.org/cli/handbook/references/internal-api/wp-cli-add-hook/
     * @param string $block_name
     * @return void
     */
    public static function createEmptyFieldGroup($block_name)
    {
        if (!function_exists('acf_add_local_field_group')) {
            /** @disregard P1009 */
            WP_CLI::error('ACF is not active.');
            return;
        }

        $title = 'Block: ' . ucfirst($block_name);
        $group_key = 'group_' . strtolower(str_replace(' ', '_', $block_name));

        /** @disregard P1010 */
        $existing = get_posts(array(
            'post_type' => 'acf-field-group',
            'name' => $group_key,
            'posts_per_page' => 1,
            'post_status' => 'publish',
        ));

        if ($existing) {
            /** @disregard P1009 */
            WP_CLI::warning("Field group '{$title}' already exists.");
            return;
        }

        /** @disregard p1010 */
        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_name' => $group_key,
            'post_type' => 'acf-field-group',
            'post_status' => 'publish',
        ));

        /** @disregard P1010 */
        if (is_wp_error($post_id)) {
            /** @disregard P1009 */
            WP_CLI::error("Failed to create field group: " . $post_id->get_error_message());
            return;
        }

        $location_rules = array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/' . $block_name,
                ),
            ),
        );

        $post_content = array(
            'location'             => $location_rules,
            'position'             => 'normal',
            'style'                => 'default',
            'label_placement'      => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen'       => '',
            'description'          => '',
            'show_in_rest'         => 0,
        );

        $serialized_content = serialize($post_content);

        /** @disregard P1010 */
        wp_update_post(array(
            'ID'           => $post_id,
            'post_content' => $serialized_content,
        ));

        /** @disregard P1009 */
        WP_CLI::success("ACF field group '{$title}' created and registered in the GUI.");
    }


    /**
     * Generates the PHP stub for the block
     *
     * @param array $args
     * @return string
     */
    public static function generateJsonStub($block_name)
    {
        // Grab our stub files
        $stubs_directory = dirname(__DIR__, 2) . '/stubs';
        $json_stub = file_get_contents($stubs_directory . "/block.json.stub", true);
        $json_stub = str_replace('{block_name}', $block_name, $json_stub);
        $json_stub = str_replace('{block_title}', ucwords(str_replace('_', ' ', $block_name)), $json_stub);

        return $json_stub;
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

        $php_file_path = $single_dir . "/{$block_name}.php";
        $scss_file_path = $single_dir . "/{$block_name}.scss";
        $json_file_path = $single_dir . "/block.json";

        if (!is_dir($blocks_dir)) {
            mkdir($blocks_dir, 0755, true);
        }

        if (!is_dir($single_dir)) {
            mkdir($single_dir, 0755, true);
        }

        return [
            'blocks_dir' => $blocks_dir,
            'single_dir' => $single_dir,
            'php_file_path' => $php_file_path,
            'scss_file_path' => $scss_file_path,
            'json_file_path' => $json_file_path
        ];
    }

    /**
     * Writes a file to the specified location
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
