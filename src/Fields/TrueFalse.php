<?php

namespace MVRK\Icenberg\Fields;

class TrueFalse extends Base
{
    /**
     * True/False fields should only be used for evaluations,
     * shouldn't be printed.
     *
     * @return void
     */
    public function getElement()
    {
        return;
    }
}
