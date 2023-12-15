<?php

namespace App\Services;

use App\Models\Level;

class LevelService
{
    public function get() {
        return Level::with([
                'multiplier', 
                'checkpoints', 
                'bonus_value'])
                    ->get();
    }
}