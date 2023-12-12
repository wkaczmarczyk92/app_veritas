<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'checkpoint'
    ];
}
