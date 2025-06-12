<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Courses\Lesson;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Test\Test;

class GermanLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'test_time'
    ];

    public function lessons(): MorphMany
    {
        return $this->morphMany(Lesson::class, 'lessonable')->orderBy('order', 'asc');
    }

    public function test(): MorphToMany
    {
        return $this->morphToMany(Test::class, 'model', 'model_has_tests', 'model_id', 'test_id');
    }
}
