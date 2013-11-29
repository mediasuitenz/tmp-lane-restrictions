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

        $startsAt = DateTime::createFromFormat('Y-m-d H:i:s', $attrs['starts_at']);
        $startsAt->setTimeZone(new DateTimeZone(getenv('TIME_ZONE')));
        $attrs['starts_at'] = $startsAt->format(DateTime::ISO8601);

        $endsAt = DateTime::createFromFormat('Y-m-d H:i:s', $attrs['ends_at']);
        $endsAt->setTimeZone(new DateTimeZone(getenv('TIME_ZONE')));
        $attrs['ends_at'] = $endsAt->format(DateTime::ISO8601);

        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $attrs['created_at']);
        $createdAt->setTimeZone(new DateTimeZone(getenv('TIME_ZONE')));
        $attrs['created_at'] = $createdAt->format(DateTime::ISO8601);

        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $attrs['updated_at']);
        $updatedAt->setTimeZone(new DateTimeZone(getenv('TIME_ZONE')));
        $attrs['updated_at'] = $updatedAt->format(DateTime::ISO8601);

        return $attrs;
    }
}
