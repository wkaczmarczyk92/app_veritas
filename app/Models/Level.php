<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Level extends Model
{
    use HasFactory;

    public function multiplier() : HasOne
    {
        return $this->hasOne(Multiplier::class);
    }

    public function checkpoints() : HasOne
    {
        return $this->hasOne(PointCheckpoint::class);
    }

    public function bonus_value() : HasOne
    {
        return $this->hasOne(LevelBonusValue::class);
    }
}
