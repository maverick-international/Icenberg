<?php

namespace MVRK\Icenberg\Fields;

class Checkbox extends Base
{
    public function getElement()
    {
        return false; // shouldn't ususally have a frontend representation
    }
}
