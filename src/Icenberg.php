<?php

namespace MVRK\Icenberg;

use MVRK\Icenberg\Fields\FlexibleContent;
use MVRK\Icenberg\Fields\Relationship;
use MVRK\Icenberg\Fields\Group;
use MVRK\Icenberg\Fields\Repeater;
use MVRK\Icenberg\Fields\Buttons;
use MVRK\Icenberg\Fields\Settings;

/**
 * Magically clean up block templates by standardising the
 * presentation of acf sub fields in flexible field blocks
 * allowing us to get on with the most important part of our
 * job which is styling 30 different variations of testimonial.
 *
 * @todo maybe bring this up one level and automatically process
 * all the fields in a given row.
 *
 * @author Dan Devine <dan.devine@maverick-intl.com>
 */
class Icenberg
{
    /**
     * The flexible content row/block name
     *
     * @var string
     */
    public $layout;

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


    public function __construct($layout)
    {
        $this->layout = $layout;
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

    public function get_buttons($field_name)
    {
        $field_object = $this->process($field_name);

        // N.B fails quietly if field doesn't exist.
        if (!$field_object) {
            return null;
        }

        return (new Buttons)->getElement($field_object, $this->layout);
    }

    public function the_buttons($field_name)
    {
        echo $this->get_buttons($field_name);
    }

    /**
     * Store the field and field objectfor method chaining.
     * Used for evaluations.
     *
     * @param string $field_name
     * @return object
     */
    public function field($field_name)
    {

        if (!get_sub_field($field_name)) {
            return;
        }

        $this->field = get_sub_field($field_name);

        $this->field_object = get_sub_field_object($field_name);

        return $this;
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
     * Checks if an interable contains a gicen condition.
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

    /**
     * Process the field.
     *
     * @param string $field the sub field name
     * @return object
     */
    protected function process($field_name)
    {
        // fails quietly if field doesn't exist.
        if (!get_sub_field($field_name)) {
            return;
        }

        $field_object = get_sub_field_object($field_name);

        // dd($field_object);

        return $field_object;
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
        $field_object = $this->process($field_name);

        // N.B fails quietly if field doesn't exist.
        if (!$field_object) {
            return null;
        }

        $type = $field_object['type'];


        if ($type === 'group') {
            return (new Group)->getElement($field_object, $this);
        }

        if ($type === 'repeater') {
            return (new Repeater)->getElement($field_object, $this);
        }

        if ($type === 'flexible_content') {
            return (new FlexibleContent)->getElement($field_object, $this);
        }

        if ($type === 'relationship') {
            return (new Relationship)->getElement($field_object, $this);
        }

        $pascal = str_replace('_', '', ucwords($type, '_'));

        $classname = "\\MVRK\Icenberg\Fields\\" . $pascal;

        return (new $classname)->getElement($field_object, $this->layout, $tag);
    }


    /**
     * Wrap up multiple icenberg elements together
     *
     * @param string $class element classname - this method will BEM it up with the layout name
     * @param array $elements array of icenberg elements
     * @param string $tag HTML tag used for enclosing element
     * @param array $attrs custom attributes to be added to the enclosing element
     * @return string
     */
    public function enclose($class, $elements = [], $tag = 'div', $attrs = [])
    {
        echo $this->get_enclose($class, $elements, $tag, $attrs);
    }

    public function get_enclose($class, $elements = [], $tag = 'div', $attrs = [])
    {
        $layout = str_replace('_', '-', $this->layout);

        $additional_classes = '';
        $additional_attrs = '';

        if (count($attrs)) {
            if (isset($attrs['class'])) { // handle classes separately as we already have a class attribute
                $additional_classes = $attrs['class'];
                unset($attrs['class']);
            }

            $additional_attrs = implode(' ', array_map(function ($key, $val) {
                return "{$key}='{$val}'";
            }, array_keys($attrs), array_values($attrs)));
        }

        return "<{$tag} class='block--{$layout}__{$class} {$additional_classes}' {$additional_attrs}>" . implode($elements)  . "</{$tag}>";
    }
}
