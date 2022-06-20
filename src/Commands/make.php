<?php

namespace MVRK\Icenberg\Commands;

class Make
{
    /**
     * Generates php and scss files
     * for icenberg friendly blocks
     *
     * @param array $args
     * @return void
     */
    public function block($args)
    {
        //crap out if human error
        if (!$args) {
            WP_CLI::error('Hey, meathead - you need to give me a snake_case block name.');
        }

        //first item is the block name so shift it off the arrray
        $name = array_shift($args);

        //kebab case scss for BEM
        $css_name = str_replace('_', '-', $name);

        $fields = "";

        $classes = ".block--{$css_name} {" . PHP_EOL;

        $classes .= ".section__inner {}" . PHP_EOL;

        $classes .= ".wrapper {}" . PHP_EOL;

        // the rest of the args are acf sub field names so we can append
        // them to the stubs.
        foreach ($args as $arg) {

            $element = "'" . $arg . "'";

            $line = '$icenberg->get_element(' . $element . ');' . PHP_EOL;

            $class = "&__" . str_replace('_', '-', $arg) . "{}" . PHP_EOL;

            $fields .= $line;

            $classes .= $class;
        }

        $classes .= '}' . PHP_EOL;

        // grab out stub files
        $php_stub = file_get_contents(__DIR__ . '/../stubs/block.php.stub', true);

        //append the icenbergs onto the stub for each specified field
        $php_stub .= $fields;

        // write the neew files
        file_put_contents(__DIR__ . "/../blocks/{$name}.php", $php_stub);

        file_put_contents(__DIR__ . "/../../../../../../src/sass/blocks/_{$css_name}.scss", $classes);
    }

    public function salute($args)
    {
        echo "hello bozo";
    }
}
