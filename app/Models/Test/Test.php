<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Models\Role;
use App\Models\Common\CompanyBranch;

use App\Models\Common\Visibility;

use App\Models\Courses\Compendium;
use App\Models\Courses\Lesson;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'name',
        'visibility_id',
        'time'
    ];

    public static function booted()
    {
        static::creating(function ($test) {
            $test->created_by = auth()->id();
        });
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function test_results(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }

    public function test_answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function visibility(): BelongsTo
    {
        return $this->belongsTo(Visibility::class);
    }

    public function roles(): MorphToMany
    {
        return $this->morphedByMany(Role::class, 'model', 'test_has_permissions', 'test_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', Role::class);
    }

    public function company_branches(): MorphToMany
    {
        return $this->morphedByMany(CompanyBranch::class, 'model', 'test_has_permissions', 'test_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', CompanyBranch::class);
    }

    public function compendium(): MorphToMany
    {
        return $this->morphedByMany(Compendium::class, 'model', 'model_has_tests', 'test_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', Compendium::class);
    }

    public function lesson(): MorphToMany
    {
        return $this->morphedByMany(Lesson::class, 'model', 'model_has_tests', 'test_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', Lesson::class);
    }
}
