<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Common\File;

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

    public function file(): MorphToMany
    {
        return $this->morphToMany(File::class, 'model', 'model_has_files', 'model_id', 'file_id');
    }

    // public function file(): ?File
    // {
    //     return $this->filesRelation()->limit(1)->first();
    // }

    // public function setFile(File $file): void
    // {
    //     // $this->filesRelation()->detach();       // usuń istniejące
    //     // $this->filesRelation()->attach($file);  // dodaj nowy

    //     $this->filesRelation()->sync([$file->id]);
    // }
}
