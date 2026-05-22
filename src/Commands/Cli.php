<?php

namespace MVRK\Icenberg\Commands;

use WP_CLI;

class Cli extends Bootstrap
{
    public function make(): void
    {
        WP_CLI::success('Icenberg make command executed.');
    }

    /**
     * Generates php and scss files for icenberg friendly blocks
     */
    public function block($args, $flags): void
    {
        if (!$args) {
            WP_CLI::error('Hey, meathead - you need to give me a block name.');
        }

        // First item is the block name, so shift it off the array
        $block_name = array_shift($args);

        // Display progress bar as a ui nicety
        $progress = WP_CLI\Utils\make_progress_bar('Generating blocks', 1);

        Block::create($block_name, $args, $flags);

        $progress->tick();
        $progress->finish();
    }
}
