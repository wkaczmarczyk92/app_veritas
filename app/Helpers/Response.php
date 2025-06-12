<?php

namespace App\Helpers;

class Response
{
    private static $_success = false;
    private static $_alert_type = 'danger';

    public static function danger($msg, $arr = [], $e = null)
    {
        return self::_response($msg, $arr, $e);
    }

    public static function success($msg, $arr = [], $e = null)
    {
        self::$_success = true;
        self::$_alert_type = 'success';
        return self::_response($msg, $arr, $e);
    }

    private static function _response($msg, $arr = [], $e = null)
    {
        $exception = [];

        if ($e) {
            $exception = [
                'exception_line' => $e->getLine(),
                'exception_msg' => $e->getMessage(),
                'exception_file' => $e->getFile(),
            ];
        }

        return array_merge(
            [
                'success'       => self::$_success,
                'alert_type'    => self::$_alert_type,
                'msg'           => $msg
            ],
            $arr,
            $exception
        );
    }
}
