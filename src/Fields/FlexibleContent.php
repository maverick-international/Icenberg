<?php

namespace MVRK\Icenberg\Fields;

/**
 * @todo - get this working for flexible content inception
 */
class FlexibleContent extends Base
{
    public function getElement($field_object, $icenberg)
    {

        $name = $field_object['_name'];


        if (have_rows($name)) :
            while (have_rows($name)) : the_row();

                dump(get_row_layout());


            endwhile;
        endif;
    }
}
