<?php

namespace MVRK\Icenberg\Fields;

class Forms extends Base
{
    public $field_object;

    public $icenberg;

    public $tag;

    public $post_id;

    public $modifiers;

    public function getElement($field_object, $icenberg, $tag, $post_id, $modifiers = [])
    {
        $form = $field_object['value'];

        $name = $field_object['_name'];

        $gravity_form = gravity_form($form['id'], $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = 0, $echo = false);

        return $this->wrap($gravity_form, $name, $icenberg, $tag, $modifiers);
    }
}
