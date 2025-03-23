<?php

namespace MVRK\Icenberg\Fields;


class Repeater
{
    public function getElement($field_object, $instance, $tag = 'div')
    {
        return "<{$tag}>Repeater Content</{$tag}>";
    }
}
