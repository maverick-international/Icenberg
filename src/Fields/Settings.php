<?php

namespace MVRK\Icenberg\Fields;

/**
 * Creates CSS classes and id from an acf group.
 */
class Settings extends Base
{
    public $settings;

    public $classes;

    protected $class_string;

    protected $id_string;

    protected $standard = [
        'unique_id', 'orientation', 'padding_bottom', 'padding_top', 'motif_background', 'section_width', 'inner_background_colour', 'section_background_colour', 'background_image'
    ];

    public function __construct($settings, $classes = [])
    {
        $this->settings = $settings;

        $this->classes = $classes;
    }

    public function applySettings()
    {
        $this->setClasses();

        $this->setId();

        return $this->build();
    }

    protected function setBackground()
    {
        if (isset($this->settings['section_background_colour'])) {
            $this->classes[] = 'block_theme--' . str_replace([' ', '-'], '_', $this->settings['section_background_colour']);
        }
    }

    protected function setInner()
    {
        if (isset($this->settings['inner_background_colour'])) {
            $this->classes[] = 'inner_theme--' . str_replace([' ', '-'], '_', $this->settings['inner_background_colour']);
        }
    }

    protected function setWidth()
    {
        if (isset($this->settings['section_width'])) {
            $this->classes[] = 'block_width--' . $this->settings['section_width'];
        }
    }

    protected function setMotif()
    {
        if (!empty($this->settings['motif_background'])) {
            $this->classes[] = 'block_has-motif';
        }
    }

    protected function setPadding()
    {
        if (isset($this->settings['padding_top']) && !$this->settings['padding_top']) {
            $this->classes[] = 'block_padding-top--0';
        }

        if (isset($this->settings['padding_bottom']) && !$this->settings['padding_bottom']) {
            $this->classes[] = 'block_padding-bottom--0';
        }
    }

    protected function setOrientation()
    {
        if (isset($this->settings['orientation'])) {
            $this->classes[] = 'block_orientation--' . $this->settings['orientation']['value'];
        }
    }

    /**
     * Puts the generated classes together.
     *
     * @return void
     */
    protected function setClasses()
    {
        $this->setBackground();

        $this->setWidth();

        $this->setMotif();

        $this->setPadding();

        $this->setOrientation();

        $this->setArbitrary();

        $classlist = strtolower(implode(' ', $this->classes));

        $this->class_string =  "class='{$classlist}' ";
    }

    /**
     * If a 'unique_id' field exists an id will be created.
     *
     * @return void
     */
    protected function setId()
    {
        if (isset($this->settings['unique_id']) && $this->settings['unique_id']) {
            $this->id_string = "id='{$this->settings['unique_id']}'";
        }
    }

    /**
     * Generates a css class for each field in a group.
     * Skips any fields that are handled specifically above, for legacy class reasons.
     *
     * @return void
     */
    protected function setArbitrary()
    {
        if ($this->settings) {
            foreach ($this->settings as $key => $value) {

                $this->parseFields($key, $value);
            }
        }
    }

    /**
     * REcursively loop through values creating clawsses.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function parseFields($key, $value)
    {
        if (!is_iterable($value)) {

            if (in_array($key, $this->standard)) {
                return;
            }

            $value = strval($value);

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if ($value) {
                $this->classes[] = "block_" . $this->unSnake($key) . '--' . $this->unSpace($value);
            }
        } elseif (is_array($value)) {

            foreach ($value as $k => $v) {

                if (!is_iterable($v)) {

                    if (in_array($key, $this->standard) || in_array($k, $this->standard)) {
                        return;
                    }

                    if (is_bool($v)) {
                        $v = $v ? 'true' : 'false';
                    }

                    if ($v) {
                        $this->classes[] = "block_" . $this->unSnake($key) . '_' . $this->unSnake($k) .
                            '--' . $this->unSpace($v);
                    }
                } else {
                    $this->parseFields($k, $v);
                }
            }
        }
    }

    /**
     * Combines the strings.
     *
     * @return void
     */
    protected function build()
    {
        return "{$this->id_string} {$this->class_string}";
    }
}
