<?php

namespace MVRK\Icenberg;

use MVRK\Icenberg\Blocks\Wrap;
use MVRK\Icenberg\Fields\Field;
use MVRK\Icenberg\Utils\CSS;
use MVRK\Icenberg\Utils\Format;

/**
 * Magically cleans up templates by standardising the presentation of acf fields, allowing us to get on
 * with the most important part of our jobs which is styling 30 different variations of testimonial.
 *
 * @author Dan Devine <dan.devine@maverick-intl.com>
 * @author Cathal Toomey <cathal.toomey@maverick-intl.com>
 */
class Icenberg
{
    public string $layout;
    public string $prefix;
    public mixed $post_id = false;
    public mixed $field;
    public mixed $field_object;

    public function __construct(string $layout, string $prefix = 'block', mixed $post_id = false)
    {
        $this->layout = $layout;
        $this->prefix = $prefix;
        $this->post_id = $post_id;
    }

    public function the_element(string $field_name, string $tag = 'div', ?array $modifiers = []): void
    {
        echo $this->findElement($field_name, $tag, $modifiers);
    }

    public function get_element(string $field_name, string $tag = 'div', ?array $modifiers = []): ?string
    {
        return $this->findElement($field_name, $tag, $modifiers);
    }

    protected function findElement(string $field_name, string $tag, ?array $modifiers = []): ?string
    {
        $post_id = $this->postId($field_name);
        $field_object = FieldObject::get($field_name, $post_id);

        return $this->getElementFromFieldObject($field_object, $tag, $post_id, $modifiers);
    }

    protected function getElementFromFieldObject(mixed $field_object, string $tag, mixed $post_id = false, ?array $modifiers = [])
    {
        if (!$field_object) {
            return null;
        }

        $field_type = $field_object['type'];
        $field_name = $field_object['_name'];
        $base_class = CSS::generateBaseFieldClass($this->layout, $field_name, $this->prefix);
        $field_classes = CSS::fieldClasses($this->layout, $field_name, $this->prefix, $modifiers);
        $type_class_name = Format::pascalCase($field_type);
        $class_name = "\\MVRK\Icenberg\Fields\\" . $type_class_name;

        if (!class_exists($class_name)) {
            return null;
        }

        return (new $class_name())->getElement(
            $field_object,
            $tag,
            $post_id,
            $field_classes,
            $base_class,
            $this
        );
    }

    public function value(string $field_name): mixed
    {
        $post_id = $this->postId($field_name);

        $field_object = FieldObject::get($field_name, $post_id);

        if (!$field_object) {
            return null;
        }

        $field_name = $field_object['_name'];

        return Field::icefield($field_name, $post_id);
    }

    public function postId(string $field_name): mixed
    {
        if (strpos($field_name, ',')) {
            return 'options';
        }

        if ($this->post_id) {
            return $this->post_id;
        }

        return null;
    }

    public function field(string $field_name): Icenberg
    {
        $post_id = $this->postId($field_name);

        return $this->processField($field_name, $post_id);
    }

    /** @noinspection PhpUndefinedFunctionInspection */
    public function processField(string $field_name, mixed $post_id = false): bool|static
    {
        if (strpos($field_name, ',')) {
            $field_name = strstr($field_name, ',', true);
            $this->post_id = 'options';
        }

        if (!get_sub_field($field_name) && !get_field($field_name, $post_id)) {
            return false;
        }

        if (get_sub_field($field_name)) {
            $this->field = get_sub_field($field_name);
            $this->field_object = get_sub_field_object($field_name);
        } elseif (get_field($field_name, $post_id)) {
            $this->field = get_field($field_name, $post_id);
            $this->field_object = get_field_object($field_name, $post_id);
        }

        return $this;
    }

    public function prune(array $exclusions): Icenberg
    {
        if (!$this->field_object) {
            return $this;
        }

        $type = $this->field_object['type'];

        $values = $this->field_object['value'] ?? null;

        if (!$values || !is_array($values)) {
            return $this;
        }

        if ('group' === $type) {
            $this->field_object['value'] = array_diff_key($values, array_flip($exclusions));
            $this->field_object['sub_fields'] = $this->pruneExcluded($this->field_object['sub_fields'], $exclusions);
        }

        if ('repeater' === $type) {
            $new_rows = [];

            foreach ($values as $row) {
                $row = array_diff_key($row, array_flip($exclusions));
                $new_rows[] = $row;
            }

            $this->field_object['value'] = $new_rows;

            $this->field_object['sub_fields'] = $this->pruneExcluded($this->field_object['sub_fields'], $exclusions);
        }

        return $this;
    }

    protected function pruneExcluded($sub_fields, $exclusions): array
    {
        return array_values(array_filter(
            $sub_fields,
            fn($field) => !in_array($field['name'], $exclusions)
        ));
    }

    protected function pruneIncluded($sub_fields, $inclusions): array
    {
        return array_values(array_filter(
            $sub_fields,
            fn($field) => in_array($field['name'], $inclusions)
        ));
    }

    public function only($inclusions): Icenberg
    {
        if (!$this->field_object) {
            return $this;
        }

        $type = $this->field_object['type'];
        $values = $this->field_object['value'] ?? null;

        if (!$values || !is_array($values)) {
            return $this;
        }

        if ('group' === $type) {
            $this->field_object['value'] = array_filter(
                $values,
                fn($field) => in_array($field, $inclusions),
                ARRAY_FILTER_USE_KEY
            );
            $this->field_object['sub_fields'] = $this->pruneIncluded($this->field_object['sub_fields'], $inclusions);
        }

        if ('repeater' === $type) {
            $new_rows = [];

            foreach ($values as $row) {
                $row = array_diff_key(array_flip($inclusions), $row);
                $new_rows[] = $row;
            }

            $this->field_object['value'] = $new_rows;

            $this->field_object['sub_fields'] = $this->pruneIncluded($this->field_object['sub_fields'], $inclusions);
        }

        return $this;
    }

    public function get(string $tag = 'div', ?array $modifiers = []): ?string
    {
        return $this->getElementFromFieldObject($this->field_object, $tag, $this->post_id, $modifiers);
    }

    public function is($condition): bool
    {
        return Predicate::is($condition, $this->field);
    }

    public function has($condition): bool
    {
        return Predicate::has($condition, $this->field);
    }

    public function greaterThan($condition): bool
    {
        return Predicate::greaterThan($condition, $this->field, $this->field_object);
    }

    public function lessThan($condition): bool
    {
        return Predicate::lessThan($condition, $this->field, $this->field_object);
    }

    public function settings(array $block_settings, ?array $classes = []): ?string
    {
        return (new Settings($block_settings, $classes))->applySettings();
    }

    public function enclose($class, $elements = [], $tag = 'div', $attrs = [], $modifiers = []): void
    {
        echo $this->get_enclose($class, $elements, $tag, $attrs, $modifiers);
    }

    public function get_enclose($class, $elements = [], $tag = 'div', $attrs = [], $modifiers = []): string
    {
        return Enclose::get($class, $this->layout, $this->prefix, $elements, $tag, $attrs, $modifiers);
    }

    public function wrap(
        mixed  $content,
        ?array $block = null,
        bool   $wrap_inner = true,
        mixed  $background = null
    ): void
    {
        echo (new Wrap($this->prefix, $this->layout, $content, $block, $wrap_inner, $background))->create();
    }

    public function member($target, $group)
    {
        return Member::get($target, $group, $this->post_id, $this);
    }

}
