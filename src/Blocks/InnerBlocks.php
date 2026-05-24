<?php

namespace MVRK\Icenberg\Blocks;

use MVRK\Icenberg\Icenberg;
use MVRK\Icenberg\Utils\CSS;

class InnerBlocks
{
    public static function make(array $allowed_blocks, Icenberg $icenberg): string
    {
        $allowed_blocks = esc_attr(wp_json_encode($allowed_blocks));
        $base_class = CSS::generateBaseClass($icenberg->layout, $icenberg->prefix);

        return "<InnerBlocks allowedBlocks='{$allowed_blocks}' className='inner-blocks {$base_class}__inner-blocks'/>";
    }

    /**
     *
     * WARNING! This is a WIP that shouldn't be used due to the instability of the various APIs.
     *
     * This applies if you want to use the built-in layout controls, use with extreme caution.
     *
     * In its infinite wisdom, WP applies the classes from supports->layout to the innerBlocks
     * in the editor view, and in their infinite wisdom ACF include these in get_block_wrapper_attributes().
     * Which of course creates an instant mismatch between front and backends.
     *
     * As the goal here is parity between the two views, we apply our own classes here.
     * make sure to add_theme_support('disable-layout-styles'); to remove the insane wp default
     * layout styles or weird shit will happen.
     *
     * As an added fun wrinkle, WP doesn't apply a class for vertical alignment at all, just some
     * CSS. Unreal.
     *
     * I observed some highly unpredictable stuff here as the supports layout implementation in WP seems
     * volatile and buggy, so ,again, use with caution.
     *
     * @todo revisit this in the future maybe
     */
    public static function getLayoutClasses($wp_block): ?string
    {
        //https://www.advancedcustomfields.com/resources/extending-acf-blocks-with-block-supports/#applying-block-supports-to-acf-blocks

        if (empty($wp_block->attributes['layout'])) {
            return null;
        }

        $layout = $wp_block->attributes['layout'];
        // "is-vertical is-content-justification-left is-layout-flex is-vertical-alignment-bottom"

        $classes = [];

        if (!empty($layout['type'])) {
            $classes[] = "is-layout-" . $layout['type'];
        }
        if (!empty($layout['justifyContent'])) {
            $classes[] = "is-content-justification-" . $layout['justifyContent'];
        }
        if (!empty($layout['verticalAlignment'])) {
            $classes[] = "is-vertical-alignment-" . $layout['verticalAlignment'];
        }
        if (!empty($layout['orientation'])) {
            $classes[] = "is-" . $layout['orientation'];
        }

        return implode(' ', $classes);
    }
}
