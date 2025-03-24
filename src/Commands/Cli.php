<?php

namespace MVRK\Icenberg\Commands;

use WP_CLI;

class Cli extends Bootstrap
{
    public function make()
    {
        /** @disregard P1009 */
        WP_CLI::success('Icenberg make command executed.');
    }

    /**
     * Generates php and scss files for icenberg friendly blocks
     *
     * @param array $args
     * @return void
     */
    public function block($args, $flags)
    {
        if (!$args) {
            /** @disregard P1009 */
            WP_CLI::error('Hey, meathead - you need to give me a snake_case block name.');
        }

        // First item is the block name, so shift it off the array
        $block_name = array_shift($args);

        // Display progress bar as a ui nicety
        /** @disregard P1010 */
        $progress = WP_CLI\Utils\make_progress_bar('Generating blocks', 1);

        Block::create($block_name, $args, $flags);

        $progress->tick();
        $progress->finish();
    }
}
