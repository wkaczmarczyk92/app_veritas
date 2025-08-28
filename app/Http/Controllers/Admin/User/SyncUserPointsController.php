<?php

namespace App\Http\Controllers\Admin\User;

use App\Services\Admin\User\SyncUserPointsService;

class SyncUserPointsController
{
    public function __invoke(int $user_id) {
        return (new SyncUserPointsService)($user_id);
    }
}