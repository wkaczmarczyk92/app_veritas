<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Models\Common\Visibility;
use App\Models\Common\File;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\User;
use App\Models\Test\Test;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'lessonable_id',
        'lessonable_type',
        'created_by',
        'visibility_id',
        'order',
        'time_to_read'
    ];

    // Zarejestruj zdarzenie 'creating' dla modelu Lesson.
    protected static function booted()
    {
        static::creating(function ($lesson) {
            // Jeżeli order został już przekazany (np. z $request->order), to nie nadpisuj.
            if (! is_null($lesson->order)) {
                return;
            }

            // Ustal najwyższy obecny 'order' dla lekcji w tym samym compendium.
            $highest_order = self::where('lessonable_id', $lesson->lessonable_id)
                ->where('lessonable_type', $lesson->lessonable_type)->max('order');

            // Ustaw 'order' nowej lekcji na najwyższy 'order' plus jeden.
            // Jeśli nie znaleziono innych lekcji, rozpocznij od 1.
            $lesson->order = $highest_order ? $highest_order + 1 : 1;
        });
    }

    public function lessonable(): MorphTo
    {
        return $this->morphTo();
    }

    public function visibility(): BelongsTo
    {
        return $this->belongsTo(Visibility::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function files(): MorphToMany
    {
        return $this->morphToMany(File::class, 'model', 'model_has_files', 'model_id', 'file_id');
    }

    public function test(): MorphToMany
    {
        return $this->morphToMany(Test::class, 'model', 'model_has_tests', 'model_id', 'test_id');
    }
}
