<?php

namespace MVRK\Icenberg\Blocks;

use MVRK\Icenberg\Settings;

/**
 * Prepare ACF Gutenberg blocks so that their structure is more flexible content
 * block-like (good, normal) and less Gutenberg block like (lame, bad).
 */
class Wrap
{
    protected static array $editor_context = [];

    public static function create(string $prefix, mixed $content, ?array $block = null, bool $wrap_inner = true, mixed $background = null): ?string
    {
        if (!$content) {
            return '';
        }

        if (is_array($content)) {
            $content = implode($content);
        }

        $outer = self::computeOuterAttrs($block, (bool)$background, $prefix);

        if ($block && isset($block['name'])) {
            self::$editor_context[$block['name']] = $outer;
        }

        $inner = self::buildInner($content, $block, $wrap_inner);

        return self::buildOuter($inner, $background, $outer);
    }

    /**
     * Hook ACF's editor wrapper filter so that WordPress's auto-generated block
     * wrapper in the editor has the same attributes as the frontend outer wrapper.
     * call this once in functions.php.
     */
    public static function register(): void
    {
        add_filter('acf/blocks/wrap_attributes', [self::class, 'filterEditorWrapAttributes'], 10, 2);
    }

    public static function filterEditorWrapAttributes(array $attributes, array $block): array
    {
        $context = self::$editor_context[$block['name']] ?? null;

        if (!$context) {
            return $attributes;
        }

        $attributes['class'] = trim(($attributes['class'] ?? '') . ' ' . ($context['class'] ?? ''));
        $attributes['data-icenberg-attribute'] = $context['data-icenberg-attribute'];

        if (!empty($context['id'])) {
            $attributes['id'] = $context['id'];
        }

        return $attributes;
    }

    protected static function computeOuterAttrs(?array $block, bool $hasBackground, string $prefix): array
    {
        $classes = [];
        $id = null;

        if ($hasBackground) {
            $classes[] = 'has-media-background';
        }

        if ($block) {
            $block_settings = get_field('block_settings') ?: null;

            if ($block_settings) {
                $settings = new Settings($block_settings, [], $prefix);
                $settings->applySettings();
                $classes = array_merge($classes, $settings->classes);

                if (!empty($block_settings['unique_id'])) {
                    $id = $block_settings['unique_id'];
                }
            }

            if (!empty($block['anchor'])) {
                $id = $block['anchor'];
            }
        }

        return [
            'class' => strtolower(trim(implode(' ', array_filter($classes)))),
            'id' => $id,
            'data-icenberg-attribute' => 'icenberg-block',
        ];
    }

    public static function buildOuter(?string $inner, ?string $background, ?array $outer = []): ?string
    {
        if ($background) {
            $content = $background . $inner;
        } else {
            $content = $inner;
        }

        if (self::isEditorRender()) {
            return $content;
        }

        $extra = [
            'class' => $outer['class'] ?? '',
            'data-icenberg-attribute' => $outer['data-icenberg-attribute'] ?? 'icenberg-block',
        ];

        if (!empty($outer['id'])) {
            $extra['id'] = $outer['id'];
        }

        $wrapper_attributes = get_block_wrapper_attributes($extra);

        return sprintf('<div %s>%s</div>', $wrapper_attributes, $content);
    }

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

    public static function buildInner(mixed $content, ?array $block, bool $wrap_inner): mixed
    {
        if (!$wrap_inner) {
            return $content;
        }

        $classes = ['wrapper'];

        if ($block && isset($block['name'])) {
            $classname = str_replace(['acf/', '_'], ['', '-'], $block['name']);
            $classes[] = 'wp-block-acf-' . $classname . '__wrapper';
        }

        return sprintf('<div class="%s">%s</div>', implode(' ', $classes), $content);
    }
}
