<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TestResult extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = [
        'test_id',
        'user_id',
        'is_passed',
        'score'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
