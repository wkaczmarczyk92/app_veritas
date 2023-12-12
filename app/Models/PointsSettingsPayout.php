<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsSettingsPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'points_to_payout'
    ];
}
