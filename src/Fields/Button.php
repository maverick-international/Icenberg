<?php

namespace MVRK\Icenberg\Fields;

class Button extends Base
{
    public $button;

    public $classes;

    public $button_background_colour;

    public $button_text_colour;

    public $button_border_colour;

    public $button_icon_background_colour;

    public $button_icon_colour;

    public $hover_background_colour;

    public $hover_text_colour;

    public $hover_border_colour;

    public $hover_icon_background_colour;

    public $hover_icon_colour;

    /**
     * Sets up the button
     *
     * @param string $field
     * @return string
     */
    public function prepareButton($field)
    {

        $this->button = get_sub_field($field);

        if (!$this->button['button_link']) {
            return;
        }

        if ($this->button['opens_modal_video']) {
            $classes[] = 'video-modal-trigger';
        }

        $is_underline = !empty($this->button['is_underline_button']);

        $this->classes = $is_underline ? ['button-underline'] : ['button'];

        $this->getColourSettings();

        return $this->buildElement();
    }

    /**
     * Retrieves all colour settings
     *
     * This is pretty OTT - think we'd be better off just defining a theme for each button tbh.
     *
     * @return void
     */
    protected function getColourSettings()
    {
        $this->button_background_colour = $this->getColourSetting('button_background_colour', 'button-background--');

        $this->button_text_colour = $this->getColourSetting('button_text_colour', 'button-text--');

        $this->button_border_colour = $this->getColourSetting('button_border_colour', 'button-border--');

        $this->button_icon_background_colour = $this->getColourSetting('button_icon_background_colour', 'button-icon--');

        $this->button_icon_colour = $this->getColourSetting('button_icon_colour', 'button-icon--');

        $this->hover_background_colour = $this->getColourSetting('hover_background_colour', 'hover-background--');

        $this->hover_text_colour = $this->getColourSetting('hover_text_colour', 'hover-text--');

        $this->hover_border_colour = $this->getColourSetting('hover_border_colour', 'hover-border--');

        $this->hover_icon_background_colour = $this->getColourSetting('hover_icon_background_colour', 'button-');

        $this->hover_icon_colour = $this->getColourSetting('hover_icon_colour', 'hover-icon--');
    }

    /**
     * Retrieves individual colour setting
     *
     * @param string $setting
     * @return void
     */
    protected function getColourSetting($setting, $prefix)
    {
        // dd($this->button);
        $colour = str_replace(' ', '_',  is_array($this->button[$setting]) ? $this->button[$setting]['label'] : $this->button[$setting]);


        if (!$colour) {
            $this->classes[] = $prefix . 'primary_colour';
        }

        $this->classes[] = $prefix . $colour;

        return;
    }

    /**
     * Puts it all together
     *
     * @return string
     */
    protected function buildElement()
    {

        $link = $this->button['button_link']['url'];

        $target = $this->button['button_link']['target'];

        $title = $this->button['button_link']['title'];

        $icon = null;

        $icon_section = null;

        $classes = strtolower(implode(' ', $this->classes));

        if (!empty($this->button['button_icon'])) {
            $icon = $this->button['button_icon'];
        }

        if ($icon) {
            $icon_section = "<span class='button__icon-image'><img src='{$icon['url']}'></span>";
        }

        $element =
            "<a href='{$link}' target='{$target}' class='{$classes}'>
            <span class='button-text'> {$title}</span>{$icon_section}</a>";

        return $element;
    }
}
