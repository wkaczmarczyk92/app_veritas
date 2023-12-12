<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multiplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'multiplier_value'
    ];

    protected $guarder = [
        'level_id'
    ];
}
