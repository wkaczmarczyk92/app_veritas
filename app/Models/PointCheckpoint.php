<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Level;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'checkpoint'
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
}
