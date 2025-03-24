<?php

namespace MVRK\Icenberg\Fields;

class ButtonGroup extends Base
{
    public function getElement()
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
