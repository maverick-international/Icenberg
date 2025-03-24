<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Config\Config;

class GoogleMap extends Base
{
    public function getElement($field_object, $layout, $tag)
    {
        $name = $field_object['_name'];

        $map = $field_object['value'] ?? null;

        $content = self::jsSetup();

        $content .= self::jsMap($map);

        $wrapped = $this->wrap($content, $name, $layout, $tag);

        return $wrapped;
    }

    public static function jsSetup()
    {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

        Config::load();

        $key = Config::get('google_maps_api_key');

        if (!$key) {
            return false;
        }

        return "<script src='https://maps.googleapis.com/maps/api/js?key={$key}&libraries=maps,marker' defer></script>";
    }

    public static function jsMap($map)
    {
        return "<gmp-map center='{$map['lat']},{$map['lng']}' zoom='{$map['zoom']}' map-id='{$map['place_id']}' style='height: 400px'>
         <gmp-advanced-marker
            position='{$map['lat']},{$map['lng']}'
            title='{$map['address']}'
        ></gmp-advanced-marker>
        </gmp-map>";
    }
}
