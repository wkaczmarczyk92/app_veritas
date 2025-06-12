<?php

namespace App\Models\Common;

use App\Models\Options\OptFor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\FileLessonData;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'extension',
        'size',
        'created_by',
        'file_mime_type_id',
        'file_type_id',
        'hash'
    ];

    public static function booted()
    {
        static::creating(function ($file) {
            $file->created_by = auth()->id();
        });
    }

    public function opt_fors(): MorphToMany
    {
        return $this->morphedByMany(OptFor::class, 'model', 'model_has_files', 'file_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', OptFor::class);
    }

    public function mime_type(): BelongsTo
    {
        return $this->belongsTo(FileMimeType::class, 'file_mime_type_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lesson_data(): HasOne
    {
        return $this->hasOne(FileLessonData::class, 'file_id', 'id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
