<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaretakerRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caretaker_first_name',
        'caretaker_last_name',
        'caretaker_email',
        'caretaker_phone_number',
        'language_id',
        'crm_lead_id',
        'crt_id_caretaker',
        'bonus_payout_completed',
        'ready_to_payout',
        'locked',
        'updated_by_user_id'
    ];

    protected $casts = [
        'language_id' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
