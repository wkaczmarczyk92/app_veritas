<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Common\File;

class FileLessonData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order',
        'file_id'
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
