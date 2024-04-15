<?php

namespace App\Models\Common;

use App\Models\Options\OptFor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'extension',
        'size',
        'created_by'
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
}
