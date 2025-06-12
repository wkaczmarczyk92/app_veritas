<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileMimeType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type',
    ];
}
