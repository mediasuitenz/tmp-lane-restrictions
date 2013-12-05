<?php
class GeoUtils {

    /**
     * Takes an originating latlng coordinate point and returns a new latlng
     * a given distance away in a given direction (bearing)
     *
     * @param  float   $lat      - latitudinal coord
     * @param  float   $lng      - longitudinal coord
     * @param  integer $bearing  - direction, possibile values are 0-360 where
     *                             0 = north, 90 = east, 180 = south, 270 = west
     * @param  float   $distance - in m away from the originating latlng
     *
     * @return array             - array of lat and lng
     */
    public static function dueCoords($lat, $lng, $bearing, $distance) {

        $distance = $distance / 1000;

        $radius = 6378.1;

        //  New lat in degrees.
        $new_lat = rad2deg(
            asin(sin(deg2rad($lat)) * cos($distance / $radius) +
                cos(deg2rad($lat)) * sin($distance / $radius) *
                    cos(deg2rad($bearing))));

        //  New lng in degrees.
        $new_lng = rad2deg(deg2rad($lng) +
            atan2(sin(deg2rad($bearing)) * sin($distance / $radius) *
                cos(deg2rad($lat)), cos($distance / $radius) -
                    sin(deg2rad($lat)) * sin(deg2rad($new_lat))));

        $coord = array();
        $coord['lat'] = $new_lat;
        $coord['lng'] = $new_lng;

        return $coord;

    }

    public static function boundingRectangle($lat, $lng, $distance) {
        return array (
            GeoUtils::dueCoords($lat, $lng, 45, $distance),
            GeoUtils::dueCoords($lat, $lng, 135, $distance),
            GeoUtils::dueCoords($lat, $lng, 225, $distance),
            GeoUtils::dueCoords($lat, $lng, 315, $distance),
        );
    }

}



