<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserHasBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level_id',
        'bonus_value',
        'completed',
        'in_progress'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level() : BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function payout_request() : HasOne
    {
        return $this->hasOne(PayoutRequest::class);
    }
}
