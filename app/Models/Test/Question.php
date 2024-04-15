<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'question',
        'created_by',
        'question_type_id',
        'order',
    ];

    public static function booted()
    {
        static::creating(function ($question) {
            $question->created_by = auth()->id();
        });
    }

    public function closed_choices(): HasMany
    {
        return $this->hasMany(QuestionClosedChoice::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(QuestionMatch::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
