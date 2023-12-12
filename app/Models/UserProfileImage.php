<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfileImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
        // 'accepted',
        'accepted_by_user_id',
        'status',
        'decline_info'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
