<?php

namespace App\Services\Test;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Models\Test\Test;

class TestDestroyService
{
    public function destroy(int $id)
    {
        $test = Test::find($id)->delete();

        if ($test) {
            return Response::success('Test został usunięty. Za chwilę zostaniesz przeniesiony...');
        } else {
            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.');
        }
    }
}
