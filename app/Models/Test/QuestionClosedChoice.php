<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionClosedChoice extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'choice',
        'is_correct',
        'question_id',
        'multiple_answers'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];
}
