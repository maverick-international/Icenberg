<?php

namespace MVRK\Icenberg\Fields;

class Forms extends Base
{

    protected $titles;

    protected $title;

    protected $description;

    protected $form;

    public $gravity_form;

    protected $layout;

    protected $tag;

    protected $name;

    public function getElement($field_object, $layout, $tag, $options)
    {
        $this->form = $field_object['value'];

        $this->name = $field_object['_name'];

        $this->layout = $layout;

        $this->tag = $tag;

        $this->getTitles();

        $this->gravity_form = gravity_form($this->form['id'], $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = 0, $echo = false);

        return $this->build();
    }

    protected function getTitles()
    {
        $this->title = $this->form['title'];

        $this->description = $this->form['description'];

        $content =  $this->wrap($this->title, $this->name . '__heading', $this->layout, 'h3');

        $content .=  $this->wrap($this->description, $this->name . '__description', $this->layout, 'p');

        $this->titles =  $this->wrap($content, $this->name . '__titles', $this->layout, $this->tag);
    }

    public function build()
    {

        $content = $this->titles;

        $content .=  $this->wrap($this->gravity_form, $this->name . '__form', $this->layout, $this->tag);

        return $this->wrap($content, $this->name, $this->layout, $this->tag);
    }
}
