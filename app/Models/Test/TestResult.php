<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = [
        'test_id',
        'user_id',
        'is_passed'
    ];
}
