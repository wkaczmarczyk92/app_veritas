<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Common\File;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class OptFor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function files(): MorphToMany
    {
        return $this->morphedByMany(File::class, 'model', 'model_has_files', 'model_id', 'file_id')->withPivot('model_type')->wherePivot('model_type', OptFor::class);
    }
}
