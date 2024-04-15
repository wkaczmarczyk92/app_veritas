<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Models\Common\Visibility;

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
}
