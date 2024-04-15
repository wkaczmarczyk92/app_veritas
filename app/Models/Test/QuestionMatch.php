<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionMatch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'match_text',
        'match_choice_file_id',
        'answer'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
