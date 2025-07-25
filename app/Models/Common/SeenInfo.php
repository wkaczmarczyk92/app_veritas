<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;

class SeenInfo extends Model
{
    // become_mittel_program - czy użytkownik widział zasady programu "Zostań Mittelem" 

    use HasFactory;

    protected $fillable = [
        'user_id',
        'info_type',
        'seen'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
