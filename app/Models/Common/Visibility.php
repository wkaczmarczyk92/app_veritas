<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Courses\Compendium;
use App\Models\Courses\Lesson;

class Visibility extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'name_pl'
    ];

    public function compendiums(): HasMany
    {
        return $this->hasMany(Compendium::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
