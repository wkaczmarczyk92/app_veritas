<?php

namespace App\Helpers;

class Response
{
    private static $_success = false;
    private static $_alert_type = 'danger';

    public static function danger($msg, $arr = []) {
        return self::_response($msg, $arr);
    }

    public static function success($msg, $arr = []) {
        self::$_success = true;
        self::$_alert_type = 'success';
        return self::_response($msg, $arr);
    }

    private static function _response($msg, $arr = []) {
        return array_merge(
            [
                'success'       => self::$_success,
                'alert_type'    => self::$_alert_type,
                'msg'           => $msg
            ],
            $arr
        );
    }
}
