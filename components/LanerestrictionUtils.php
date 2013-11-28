<?php
class LanerestrictionUtils {
    public static function lanerestrictionArray(OSMLaneRestriction $laneRestriction) {

        //get the object properties as an array
        $attrs = $laneRestriction->attributes;

        //tidy up: convert types
        $attrs['id'] = (int)$attrs['id'];
        $attrs['type_id'] = (int)$attrs['type_id'];
        $attrs['osm_node_a_id'] = (int)$attrs['osm_node_a_id'];
        $attrs['osm_node_b_id'] = (int)$attrs['osm_node_b_id'];
        $attrs['osm_node_a_version_id'] = (int)$attrs['osm_node_a_version_id'];
        $attrs['osm_node_b_version_id'] = (int)$attrs['osm_node_b_version_id'];
        $attrs['a_to_b_is_closed'] = (boolean)$attrs['a_to_b_is_closed'];
        $attrs['b_to_a_is_closed'] = (boolean)$attrs['b_to_a_is_closed'];
        $attrs['a_to_b_speed_limit'] =
            is_null($attrs['a_to_b_speed_limit']) ?
                null : (int)$attrs['a_to_b_speed_limit'];
        $attrs['b_to_a_speed_limit'] =
            is_null($attrs['b_to_a_speed_limit']) ?
                null : (int)$attrs['b_to_a_speed_limit'];

        return $attrs;
    }
}
