<?php

namespace MVRK\Icenberg;

use MVRK\Icenberg\Blocks\Wrap;
use MVRK\Icenberg\Fields\FlexibleContent;
use MVRK\Icenberg\Fields\Group;
use MVRK\Icenberg\Fields\Repeater;
use MVRK\Icenberg\Fields\Buttons;
use MVRK\Icenberg\Fields\Settings;
use MVRK\Icenberg\Fields\Base;

/**
 * Magically cleans up templates by standardising the
 * presentation of acf fields, allowing us to get on with the
 * most important part of our jobs which is styling 30
 * different variations of testimonial.
 *
 * @author Dan Devine <dan.devine@maverick-intl.com>
 * @author Cathal Toomey <cathal.toomey@maverick-intl.com>
 */
class Icenberg
{
    /**
     * The flexible content row/block name
     * primarily used for generating css classes
     *
     * @var string
     */
    public $layout;

    /**
     * The top level BEM block used in
     * constructing the css classes
     * e.g 'block' will prepend 'block--' to the class.
     *
     * @var string
     */
    public $prefix = 'block--';

    /**
     * The post ID, defaults to current post ID
     * if blank.
     * @var string
     */
    public $post_id = false;

    /**
     * The value of the acf sub field.
     *
     * @var mixed
     */
    public $field;

    /**
     * the acf sub field object.
     *
     * @var object
     */
    public $field_object;

    public function __construct($layout, $prefix = 'block', $post_id = false)
    {
        $this->layout = $layout;
        $this->post_id = $post_id;
        if ($prefix === false) {
            $this->prefix = false; // trust me this is needed
        } else {
            $this->prefix = $prefix . '--';
        }
    }

    /**
     * Echoes out the wrapped up and formatted element
     *
     * @param string $field
     * @return void
     */
    public function the_element($field_name, $tag = 'div')
    {
        echo $this->sortElement($field_name, $tag);
    }

    /**
     * Returns the wrapped up and formatted element
     *
     * @param string $field - The name of the sub field
     * @return string
     */
    public function get_element($field_name, $tag = 'div')
    {
        return $this->sortElement($field_name, $tag);
    }

    /**
     * Return the 'raw' value of a given field
     *
     * @param string $field_name
     * @return mixed
     */
    public function value($field_name)
    {
        $field_object = $this->getFieldObject($field_name);

        $post_id = $this->postId($field_name);

        if (!$field_object) {
            return;
        }

        $name = $field_object['_name'];

        return Base::icefield($name, $post_id);
    }

    /**
     *  Retuns the post->ID to enable access outside of the loop,
     *  or If there's a comma, its an option so we return that.
     *
     *  We need this to pass 'options' down to field level as the post
     *  object gives no indication of whther a given field is from options
     *  or not, sadly.
     *
     * @param string $field_name
     * @return ?string
     */
    public function postId($field_name)
    {
        if (strpos($field_name, ',')) {
            return 'options';
        }

        if ($this->post_id) {
            return $this->post_id;
        }
    }

    /**
     * Find out what type of field we're
     * dealing with and route accordingly.
     * Depends on the magic of consistent naming.
     * Indvivdual field types can be overriden here.
     *
     * @param object $field
     * @return string
     */
    protected function sortElement($field_name, $tag)
    {
        $field_object = $this->getFieldObject($field_name);

        $post_id = $this->postId($field_name);

        return $this->getElementFromFieldObject($field_object, $tag, $post_id);
    }


    protected function getFieldObject($field_name)
    {
        if (strpos($field_name, ',')) {
            $args = array_map('trim', explode(',', $field_name));
            return $this->processFieldObject(...$args);
        } else {
            return $this->processFieldObject($field_name);
        }
    }

    /**
     * Set properties for method chaining.
     * Used for evaluations.
     *
     * @param string $field_name
     * @return Icenberg
     */
    public function field($field_name)
    {
        $this->post_id = $this->postId($field_name);

        if (strpos($field_name, ',')) {
            $args = array_map('trim', explode(',', $field_name));
            return $this->processField(...$args);
        } else {
            return $this->processField($field_name, null);
        }
    }

    /**
     * Check if a field exists, discover if it's a field or sub field,
     * retrieve its field object and set the class properties for method chaining.
     *
     * @param mixed $field_name
     * @param mixed $post_id
     * @return Icenberg
     */
    public function processField($field_name, $post_id = false)
    {

        if (!get_sub_field($field_name) && !get_field($field_name, $post_id)) {
            return;
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

    /**
     * Discover if the field is a field or sub field,
     * check if it exists and get its field object.
     *
     * @param string $field the field name
     * @param ?string $post_id - is this a globl field or outside the loop?
     * @return ?object $field_object ACF Field object | null
     *
     */
    protected function processFieldObject($field_name, $post_id = false)
    {
        $field_object = null;

        if (!get_sub_field($field_name) && !get_field($field_name, $post_id)) {
            return;
        }

        if (get_sub_field($field_name)) {
            $field_object = get_sub_field_object($field_name);
        } elseif (get_field($field_name, $post_id)) {
            $field_object = get_field_object($field_name, $post_id);
        }

        return $field_object;
    }

    /**
     * Remove unwanted sub fields from
     * group or repeater fields
     *
     * @param array $exclusions - unwanted fields
     * @return Icenberg
     */
    public function prune($exclusions)
    {
        if (!$this->field_object) {
            return $this;
        }

        $type = $this->field_object['type'];

        $values = $this->field_object['value'] ?? null;

        if (!$values || !is_array($values)) {
            return $this;
        }

        if ($type === 'group') {
            $this->field_object['value'] = array_diff_key($values, array_flip($exclusions));
            $this->field_object['sub_fields'] = $this->pruneExcluded($this->field_object['sub_fields'], $exclusions);
        }

        if ($type === 'repeater') {
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

    /**
     * Perform the exclusions
     *
     * @param array $sub_fields
     * @param array $exclusions - unwanted fields
     * @return array
     */
    protected function pruneExcluded($sub_fields, $exclusions)
    {
        return array_values(array_filter(
            $sub_fields,
            fn($field) => !in_array($field['name'], $exclusions)
        ));
    }

    /**
     * Perform the inclusions
     *
     * @param array $sub_fields
     * @param array $inclusions - whitelisted fields
     * @return array
     */
    protected function pruneIncluded($sub_fields, $inclusions)
    {
        return array_values(array_filter(
            $sub_fields,
            fn($field) => in_array($field['name'], $inclusions)
        ));
    }

    /**
     * Prune non-whitelisted sub fields
     * from a repeater or group
     *
     * @param array $inclusions - wanted fields
     * @return Icenberg
     */
    public function only($inclusions)
    {
        if (!$this->field_object) {
            return $this;
        }

        $type = $this->field_object['type'];

        $values = $this->field_object['value'] ?? null;

        if (!$values || !is_array($values)) {
            return $this;
        }

        if ($type === 'group') {
            $this->field_object['value'] = array_filter(
                $values,
                fn($field) => in_array($field, $inclusions),
                ARRAY_FILTER_USE_KEY
            );
            $this->field_object['sub_fields'] = $this->pruneIncluded($this->field_object['sub_fields'], $inclusions);
        }

        if ($type === 'repeater') {
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

    /**
     * Delivers the icenberged string from an Icenberg
     * field instance, at the end of the chain
     *
     * @param string $tag
     * @return ?string
     */
    public function get($tag = 'div')
    {
        return $this->getElementFromFieldObject($this->field_object, $tag, $this->post_id);
    }

    /**
     * Undocumented function
     *
     * @param object $field_object
     * @param ?string $tag
     * @param ?string $post_id
     * @return ?string
     */
    protected function getElementFromFieldObject($field_object, $tag, $post_id = false)
    {
        // N.B fails quietly if field doesn't exist.
        if (!$field_object) {
            return null;
        }

        $type = $field_object['type'];

        /**
         * Groups and Repeaters require special treatment
         */

        if ($type === 'group') {
            return (new Group())->getElement($field_object, $this, $tag, $post_id);
        }

        if ($type === 'repeater') {
            return (new Repeater())->getElement($field_object, $this, $tag, $post_id);
        }

        /**
         * This is a dead end (for now)
         */
        if ($type === 'flexible_content') {
            return (new FlexibleContent())->getElement($field_object, $this, $tag,  $post_id);
        }

        $pascal = str_replace('_', '', ucwords($type, '_'));

        $classname = "\\MVRK\Icenberg\Fields\\" . $pascal;

        return (new $classname())->getElement($field_object, $this, $tag, $post_id);
    }

    /**
     * Checks equivalence
     *
     * @param string|int $condition
     * @return boolean
     */
    public function is($condition)
    {
        if (!$this->field) {
            return false;
        }

        if (!$this->field === $condition) {
            return false;
        }

        return true;
    }

    /**
     * Checks if an interable contains a given condition.
     *
     * @param string|int $condition
     * @return boolean
     */
    public function has($condition)
    {
        if (!$this->field) {
            return false;
        }

        if (is_iterable($this->field)) {
            foreach ($this->field as $item) {
                if ($item === $condition) {
                    return true;
                }
            }
        }
    }

    /**
     * Checks if a numeric field is greater
     * than a given condition, as implied
     * by the method name.
     *
     * @param int $condition
     * @return void
     */
    public function greaterThan($condition)
    {
        if (!$this->field) {
            return false;
        }

        /**
         * just'numeric' fields thanks.
         * They return strings btw.
         */
        $allowed = [
            'range',
            'number'
        ];

        if (!in_array($this->field_object['type'], $allowed)) {
            return false;
        }

        if (!intval($this->field) < $condition) {
            return false;
        }

        return true;
    }

    /**
     * Checks if a numeric field is less
     * than a given condition, as implied
     * by the method name.
     *
     * @param int $condition
     * @return void
     */
    public function lessThan($condition)
    {
        if (!$this->field) {
            return false;
        }

        /**
         * just'numeric' fields thanks.
         * They return strings btw.
         */
        $allowed = [
            'range',
            'number'
        ];

        if (!in_array($this->field_object['type'], $allowed)) {
            return false;
        }

        if (!intval($this->field) > $condition) {
            return false;
        }

        return true;
    }

    public function settings($block_settings, $classes)
    {
        return (new Settings($block_settings, $classes))->applySettings();
    }


    public function get_buttons($field_name)
    {
        $field_object = $this->processFieldObject($field_name);

        // N.B fails quietly if field doesn't exist.
        if (!$field_object) {
            return null;
        }

        return (new Buttons())->getElement($field_object, $this);
    }

    public function the_buttons($field_name)
    {
        echo $this->get_buttons($field_name);
    }

    /**
     * Wrap up multiple icenberg elements together
     *
     * @param string $class element classname - BEM it up with the layout name
     * @param array $elements array of icenberg elements
     * @param string $tag HTML tag used for enclosing element
     * @return string
     */
    public function enclose($class, $elements = [], $tag = 'div')
    {
        echo $this->get_enclose($class, $elements, $tag);
    }

    public function get_enclose($class, $elements = [], $tag = 'div')
    {
        $layout = str_replace('_', '-', $this->layout);

        if ($class) {
            return "<{$tag} class='{$this->prefix}{$layout}__{$class}'>" . implode($elements)  . "</{$tag}>";
        } else {
            return "<{$tag} class='{$this->prefix}{$layout}'>" . implode($elements)  . "</{$tag}>";
        }
    }

    /**
     * Wrap a Gutenberg block
     *
     * @param [type] $content
     * @param [type] $block_settings
     * @param boolean $wrap_inner
     * @return void
     */
    public static function wrap($content, $block_settings = null, $wrap_inner = true)
    {
        echo Wrap::create($content, $block_settings, $wrap_inner);
    }
}
