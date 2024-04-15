<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'text_answer',
        'for_approval',
        'choice_answer_id',
        'multiple_choice_answer_ids',
        'is_correct',
        'user_id'
    ];
}
