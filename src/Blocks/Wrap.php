<?php

namespace MVRK\Icenberg\Blocks;

use MVRK\Icenberg\Fields\Settings;
use MVRK\Icenberg\Icenberg;

/**
 * Prepare ACF Gutenberg blocks so that their structure
 * is more flexible content block like (good, normal) and
 * less Gutenberg block like (lame, bad).
 */
class Wrap
{
    /**
     * We want to wrap our block on the frontend but not in the wordpress
     * editor where its already wrapped. This makes consistent styling
     * possible across both, theoretically anywway.
     *
     * @param [type] $content
     * @return void
     */
    public static function create($content, $block = null, $wrap_inner = true, $background = null)
    {
        if (!$content) {
            return '';
        }

        if (is_array($content)) {
            $content = implode($content);
        }

        $inner = self::buildInner($content, $block, $wrap_inner);

        return self::buildOuter($inner, $background);
    }

    /**
     * matches the outer wrapper that wordpress puts over the block
     * in the preview screen (but not on the frontend).
     *
     * native settings are applied here.
     *
     * @param [type] $content
     * @return void
     */
    public static function buildOuter($inner, $background)
    {
        if ($background) {
            $content  = $background . $inner;
        } else {
            $content = $inner;
        }

        if (is_admin() && acf_is_block_editor()) {
            return $content;
        }

        $wrapper_attributes = get_block_wrapper_attributes();

        return sprintf('<div %s>%s</div>', $wrapper_attributes, $content);
    }

    /**
     * Wraps content in an internal wrapper with non default
     * settings applied via an icenberg settings field.
     *
     */
    public static function buildInner($content, $block, $wrap_inner)
    {
        $settings = '';

        if ($block) {
            $classname = str_replace('acf/', '', $block['name']);

            $classname = str_replace('_', '-', $classname);

            $classes = ['wrapper', 'wp-block-acf-' . $classname . "__wrapper"];

            $block_settings = null;

            if (get_field('block_settings')) {
                $block_settings = get_field('block_settings');
            }

            $settings = (new Icenberg($block['title']))->settings($block_settings, $classes);

            if (isset($block['anchor'])) {
                $id = " id='{$block['anchor']}'";

                $settings .= $id;
            }
        }

        if ($wrap_inner) {
            $content =  sprintf('<div %s>%s</div>', $settings, $content);
        }

        return $content;
    }
}
