<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CRMPlaner;

class Offer extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'crm_offer_id',
        'hp_code',
        'start_date',
        'crm_family_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function planer(): BelongsTo
    {
        return $this->belongsTo(CRMPlaner::class, 'crm_offer_id', 'pnr_id_planer');
    }
}
