<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\Courses\Lesson;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Common\Visibility;

use Spatie\Permission\Models\Role;
use App\Models\Common\CompanyBranch;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Compendium extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'time_to_read',
        'compendium_type_id',
        'visibility_id',
        'created_by'
    ];

    protected static function booted()
    {
        static::deleting(function ($compendium) {
            $compendium->lessons()->delete();
        });
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CompendiumType::class, 'compendium_type_id');
    }

    public function lessons(): MorphMany
    {
        return $this->morphMany(Lesson::class, 'lessonable')->orderBy('order', 'asc');
    }

    public function visibility(): BelongsTo
    {
        return $this->belongsTo(Visibility::class);
    }

    public function roles(): MorphToMany
    {
        return $this->morphedByMany(Role::class, 'model', 'compendium_has_permissions', 'compendium_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', Role::class);
        // return $this->morpheByMany(Role::class, 'model', 'compendium_has_permissions', 'compendium_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', Role::class);
    }

    public function company_branches(): MorphToMany
    {
        return $this->morphedByMany(CompanyBranch::class, 'model', 'compendium_has_permissions', 'compendium_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', CompanyBranch::class);
        // return $this->morpheByMany(CompanyBranch::class, 'model', 'compendium_has_permissions', 'compendium_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', CompanyBranch::class);
    }
}
