<?php

namespace MVRK\Icenberg\Fields;

class Group
{
    public function getElement($field_object, $instance, $tag = 'div')
    {
        return "<{$tag}>Group Content</{$tag}>";
    }
}
