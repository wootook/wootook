<?php


class Wootook_Core_Time
{
    public static function init($timezone = null)
    {
        if ($timezone === null) {
            $timezone = Wootook::getConfig('system/date/timezone');
        }
        if ($timezone === null) {
            $timezone = 'GMT';
        }

        date_default_timezone_set($timezone);
    }
}