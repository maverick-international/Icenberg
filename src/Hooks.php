<?php

namespace MVRK\Icenberg;

use MVRK\Icenberg\Config\Config;

class Hooks
{
    public static function init(): void
    {
        add_action('enqueue_block_editor_assets', [Hooks::class, 'addPrefixToBlock']);
    }

    /**
     * We need to use js to force WordPress to add our custom block classes.
     * Initialise in functions.php or wherever.
     *
     * The use of a config value can potentially lead to a mismatch in classes if the user defines a different prefix
     * when initalising icenberg but can't think of a good way around that, so caveat emptor.
     *
     * @link https://developer.wordpress.org/block-editor/reference-guides/packages/packages-compose/
     * @link https://www.php.net/manual/en/language.types.string.php
     */
    public static function addPrefixToBlock(): void
    {
        $prefix = Config::get('block_editor_prefix') ?? 'block';
        $prefixed_class = $prefix . ' ' . $prefix . '--';

        // the lesser spotted heredoc.
        $script = <<<JS
            wp.hooks.addFilter(
                'editor.BlockListBlock',
                'icenberg/add-prefix-to-block',
                 wp.compose.createHigherOrderComponent(function (BlockListBlock) {
                    return function (props) {
                        let name = props.name || '';
                        let prefix = '{$prefixed_class}';
                        let slug = name.replace('acf/', '');
                        let extra = name.startsWith('acf/') ? prefix + slug : '';
                        return wp.element.createElement(BlockListBlock, Object.assign({}, props, {
                            className: ((props.className || '') + ' ' + extra).trim()
                        }));
                    };
                }, 'addCustomClass')
            );
            JS;

        wp_add_inline_script('wp-blocks', $script);
    }
}
