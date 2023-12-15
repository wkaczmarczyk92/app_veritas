<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\UserPoint;

class UserPointService
{
    public function get_last_insert_date() {
        $last_insert_date = UserPoint::where('auto', true)
            ->orderBy('created_at', 'desc')
            ->first();

        return date('Y-m-d', strtotime($last_insert_date->created_at));
    }
}