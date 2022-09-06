<?php

namespace MVRK\Icenberg\Fields;

class Password extends Base
{
    /**
     * Don't display passwords!
     *
     * @return void
     */
    public function getElement()
    {
        return false;
    }
}
