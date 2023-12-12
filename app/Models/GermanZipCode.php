<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GermanZipCode extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'land_id',
        'zip_code',
    ];
}
