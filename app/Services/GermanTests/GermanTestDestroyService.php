<?php

namespace App\Services\GermanTests;

use App\Helpers\Transaction;
use App\Models\Test\Test;

class GermanTestDestroyService
{
    public function __invoke(int $id)
    {
        return Transaction::try(
            function () use ($id) {
                $test = Test::find($id);
                $test->delete();
            },
            'Test został usunięty.'
        );
    }
}
