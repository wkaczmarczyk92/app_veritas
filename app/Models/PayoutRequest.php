<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'user_id',
        'payout_value',
        'user_has_bonus_id'
        // 'used_points',
        // 'payment_completed'
    ];

    // public function user() : BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    public function user_has_bonus() : BelongsTo
    {
        return $this->belongsTo(UserHasBonus::class);
    }

    public function admin_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_completed_by');
    }
}
