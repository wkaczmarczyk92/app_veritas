<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class OralExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'time',
        'exam_at',
        'created_by',
        'is_passed',
        'score'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'time' => 'integer',
        'exam_at' => 'date',
        'created_by' => 'integer',
        'is_passed' => 'boolean',
        'score' => 'string'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function created_by() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
