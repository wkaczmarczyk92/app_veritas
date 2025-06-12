<?php

namespace App\Helpers;

class Cookie
{
    private static array $_time_periods = [
        'day'       => 86400,
        'week'      => 604800,
        'month'     => 2592000,
        'year'      => 31536000,
    ];

    public static function exists($name)
    {
        return (isset($_COOKIE[$name]) and $_COOKIE[$name] !== '' and $_COOKIE[$name] !== null);
    }

    public static function set($name, $value, $period = 'week')
    {
        $time_period = self::_time_period($period);

        if ($time_period === false) {
            throw new \Exception('Invalid time period');
        }

        setcookie($name, $value, $time_period, '/');
    }

    public static function unset($name)
    {
        setcookie($name, '', time() - 3600, '/');
    }

    private static function _time_period($period)
    {
        return array_key_exists($period, self::$_time_periods) ? self::$_time_periods[$period] : (is_integer($period) ? (time() + $period) : false);
    }
}
