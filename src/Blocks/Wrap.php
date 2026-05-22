<?php

namespace MVRK\Icenberg\Blocks;

use MVRK\Icenberg\Settings;

/**
 * Prepare ACF Gutenberg blocks so that their structure is more flexible content
 * block-like (good, normal) and less Gutenberg block like (lame, bad).
 */
class Wrap
{
    private string $prefix;
    private string $layout;
    private mixed $content;
    private ?array $block;
    private bool $wrap_inner;
    private mixed $background;
    private string $base_class;

    public function __construct(string $prefix, string $layout, mixed $content, ?array $block = null, bool $wrap_inner = true, mixed $background = null)
    {
        $this->content = $content;
        $this->block = $block;
        $this->wrap_inner = $wrap_inner;
        $this->background = $background;
        $this->prefix = $prefix;
        $this->layout = $layout;
        $this->base_class = $this->getBaseClass($block, $prefix);
    }

    protected function getBaseClass($block, $prefix): string
    {
        if ($block) {
            $base_class = str_replace(['acf/', '_'], ['', '-'], $block['name']);

        } else {
            $base_class = $this->layout;
        }

        if ($prefix) {
            $base_class = $prefix . '--' . $base_class;
        }

        return $base_class;
    }

    public function create(): ?string
    {
        if (!$this->content) {
            return '';
        }

        $content = $this->content;

        if (is_array($content)) {
            $content = implode($content);
        }

        $inner = $this->innerWrapper($content);
        $outer = $this->intermediateWrapper($inner, $this->background);

        if (!self::isEditorRender()) {
            return $this->frontEndShell($outer);
        }
        return $outer;
    }

    /**
     * As we can't realistically put our block settings on the outer layer in the block editor we use an intermediate
     * wrapper. Settings here can be inherited by the content, and it can inherit the settings from its parent which is
     * the default wp wrapper in the editor And our clone 'shell' on the frontend.
     *
     * @noinspection PhpUndefinedFunctionInspection
     */
    protected function intermediateWrapper($content, $background): string
    {
        $classes = [];
        $classes[] = $this->prefix . '__inner';
        $classes[] = $this->base_class . '__inner';

        if ($background) {
            $content = $background . $content;
        }

        if ($this->background) {
            $classes[] = 'has-media-background';
        }

        if ($this->block) {
            $block_settings = get_field('block_settings') ?: null;

            if ($block_settings) {
                $settings = new Settings($block_settings, [], $this->prefix);
                $settings->applySettings();
                $classes = array_merge($classes, $settings->classes);
            }
        }

        return sprintf('<div class="%s">%s</div>', implode(' ', $classes), $content);
    }

    /**
     * The main content that is (optionally) wrapped away from the edge of the block.
     */
    public function innerWrapper(string $content): string
    {
        if (!$this->wrap_inner) {
            return $content;
        }

        $classes = ['wrapper'];
        $classes[] = $this->base_class . '__wrapper';

        return sprintf('<div class="%s">%s</div>', implode(' ', $classes), $content);
    }

    /**
     * Used on frontend only, mimics the default WP wrapper that only appears in the editor.
     * See Hooks class for how the prefixes are sent to WP.
     */
    public function frontEndShell(?string $content): string
    {
        $extra = [
            'class' => $this->prefix . " " . $this->base_class,
            'data-icenberg-attribute' => 'icenberg-block',
        ];

        $wrapper_attributes = get_block_wrapper_attributes($extra);

        return sprintf('<div %s>%s</div>', $wrapper_attributes, $content);
    }

    /**
     * Are we in the editor? I hope not.
     *
     * @noinspection PhpUndefinedFunctionInspection
     */
    protected static function isEditorRender(): bool
    {
        if (is_admin()) {
            return true;
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }

        if (function_exists('acf_is_block_editor') && acf_is_block_editor()) {
            return true;
        }

        return false;
    }

}
