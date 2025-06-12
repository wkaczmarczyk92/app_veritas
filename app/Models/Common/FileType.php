<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class FileType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
