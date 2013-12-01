<?php
class DateTimeUtils {
    public static function timeStamp() {
        $timeZoneString = LanerestrictionUtils::moduleParam('serverTimeZone');
        $timeZone = new DateTimeZone($timeZoneString);
        $dateTime = new DateTime('now', $timeZone);
        $format = LanerestrictionUtils::moduleParam('apiDateTimeOutputFormat');
        return $dateTime->format($format);
    }
    public static function timeStampFromNow(DateInterval $interval) {
        $timeZoneString = LanerestrictionUtils::moduleParam('serverTimeZone');
        $timeZone = new DateTimeZone($timeZoneString);
        $dateTime = new DateTime('now', $timeZone);
        $dateTime->add($interval);
        $format = LanerestrictionUtils::moduleParam('apiDateTimeOutputFormat');
        return $dateTime->format($format);
    }
    public static function toMysql($timestamp, $format) {
        $timeZoneString = LanerestrictionUtils::moduleParam('serverTimeZone');
        $timeZone = new DateTimeZone($timeZoneString);
        $dateTime = DateTime::createFromFormat($format, $timestamp, $timeZone);
        return $dateTime->format('Y-m-d H:i:s');
    }
}
