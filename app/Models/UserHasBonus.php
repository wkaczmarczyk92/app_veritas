<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\BonusStatus;

class UserHasBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level_id',
        'bonus_value',
        'completed',
        'in_progress',
        'bonus_status_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function payout_request(): HasOne
    {
        return $this->hasOne(PayoutRequest::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BonusStatus::class, 'bonus_status_id', 'id');
    }
}
