<?php

namespace MVK\Icenberg\Fields;


class Settings extends Base
{
    public $settings;

    public $classes;

    protected $class_string;

    protected $id_string;

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
            $this->classes[] = 'block_width-' . $this->settings['section_width'];
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
            $this->classes[] = 'block_padding-top-0';
        }

        if (isset($this->settings['padding_bottom']) && !$this->settings['padding_bottom']) {
            $this->classes[] = 'block_padding-bottom-0';
        }
    }

    protected function setOrientation()
    {
        if (isset($this->settings['orientation'])) {
            $this->classes[] = 'block_orientation-' . $this->settings['orientation']['value'];
        }
    }

    protected function setClasses()
    {
        $this->setBackground();

        $this->setWidth();

        $this->setMotif();

        $this->setPadding();

        $this->setOrientation();

        $classlist = strtolower(implode(' ', $this->classes));

        $this->class_string =  "class='{$classlist}' ";
    }

    protected function setId()
    {
        if (isset($this->settings['unique_id']) && $this->settings['unique_id']) {
            $this->id_string = "id='{$this->settings['unique_id']}'";
        }
    }

    protected function build()
    {
        return "{$this->id_string} {$this->class_string}";
    }
}
