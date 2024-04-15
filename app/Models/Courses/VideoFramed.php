<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class VideoFramed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vimeo_video_id',
        'user_id',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($video) {
            $video->user_id = auth()->id();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
