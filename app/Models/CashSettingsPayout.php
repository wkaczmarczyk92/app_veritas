<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashSettingsPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_cash'
    ];
}
