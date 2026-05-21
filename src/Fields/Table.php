<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

/**
 *
 * @link https://wordpress.org/plugins/advanced-custom-fields-table-field/
 */
class Table extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $field_name = $field_object['_name'];
        $table = self::icefield($field_name, $post_id);

        if (empty($table)) {
            return '';
        }

        $content = "<table>";

        if (!empty($table['caption'])) {
            $content .= "<caption>{$table['caption']}</caption>";
        }

        if (!empty($table['header'])) {
            $content .= "<thead><tr>";
            foreach ($table['header'] as $th) {
                $content .= "<th>{$th['c']}</th>";
            }
            $content .= "</tr></thead>";
        }

        if (!empty($table['body'])) {
            $content .= "<tbody>";
            foreach ($table['body'] as $tr) {
                $content .= "<tr>";
                foreach ($tr as $td) {
                    $content .= "<td>{$td['c']}</td>";
                }
                $content .= "</tr>";
            }
            $content .= "</tbody>";
        }
        
        $content .= "</table>";

        return $this->wrap($field_name, $tag, $post_id, $field_classes, $content);
    }
}
