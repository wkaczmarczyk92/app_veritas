<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use Exception;

class Transaction
{
    public static function try(
        callable $callback,
        string $success_msg,
        string $error_msg = 'Coś poszło nie tak. Spróbuj ponownie później.'
    ) {
        try {
            DB::beginTransaction();

            $result = $callback();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger($error_msg, e: $e);
        }

        return Response::success($success_msg, is_array($result) ? $result : []);
    }
}
