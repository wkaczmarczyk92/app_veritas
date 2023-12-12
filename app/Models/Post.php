<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'order',
        'start_at',
        'end_at',
        'type',
        'label_id'
    ];

    public function post_labels() : HasOne
    {
        return $this->hasOne(PostLabel::class, 'id', 'label_id');
    }
}
