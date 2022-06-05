<?php

namespace MVRK\Icenberg\Fields;

class Relationship extends Base
{
    /**
     * Relationships can't work in icenberg
     * becasue it takes us out of the_row(),
     * (its not you, its me).
     *
     */
    public function getElement($field, $icenberg)
    {
        return;
    }
}
