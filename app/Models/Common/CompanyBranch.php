<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Courses\Compendium;

class CompanyBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by'
    ];

    public $timestamps = false;

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_company_branches', 'company_branch_id', 'model_id')->withPivot('model_type')->wherePivot('model_type', User::class);
    }

    public function compendia(): MorphToMany
    {
        return $this->morphToMany(Compendium::class, 'model', 'compendium_has_permissions', 'model_id', 'compendium_id')->withPivot('model_type')->wherePivot('model_type', CompanyBranch::class);
    }

    public function tests(): MorphToMany
    {
        return $this->morphToMany(Compendium::class, 'model', 'test_has_permissions', 'model_id', 'test_id')->withPivot('model_type')->wherePivot('model_type', CompanyBranch::class);
    }
}
