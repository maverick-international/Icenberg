<?php

namespace MVRK\Icenberg\Commands;

use WP_CLI;
use WP_CLI_Command;

class Cli extends Bootstrap
{
    public function make()
    {
        WP_CLI::success('Icenberg make command executed.');
    }

    /**
     * Generates php and scss files for icenberg friendly blocks
     *
     * @param array $args
     * @return void
     */
    public function block($args)
    {
        // Exit early if no arguments
        if (!$args) {
            WP_CLI::error('Hey, meathead - you need to give me a snake_case block name.');
        }

        // First item is the block name, so shift it off the array
        $block_name = array_shift($args);

        // Display progress bar (simple for now)
        $progress = WP_CLI\Utils\make_progress_bar('Generating blocks', 1);

        // Create the block via the Block class
        Block::create($block_name, $args);

        $progress->tick();
        $progress->finish();
    }
}
