<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'family_last_name',
        'country_code',
        'phone_number',
        'info',
        'processing_personal_data',
        'rejected_text',
        'rejected'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
