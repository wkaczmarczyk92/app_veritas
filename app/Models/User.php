<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Spatie\Permission\Traits\HasRoles;

use App\Models\Courses\VideoFramed;

use App\Models\Common\CompanyBranch;
use App\Models\CRM\CaretakerBlackList;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Models\Common\SeenInfo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pesel',
        'email',
        'password',
        'active',
        'activated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['is_admin'];

    protected function getIsAdminAttribute()
    {
        return $this->hasAnyRole(['god_mode', 'admin', 'super-admin']);
    }

    public function user_profiles(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function user_points(): HasMany
    {
        return $this->hasMany(UserPoint::class);
    }

    public function ready_to_departure_dates(): HasOne
    {
        return $this->hasOne(ReadyToDepartureDate::class);
    }

    public function caretaker_recommendation(): HasMany
    {
        return $this->hasMany(CaretakerRecommendation::class);
    }

    public function family_recommendation(): HasMany
    {
        return $this->hasMany(FamilyRecommendation::class);
    }

    public function user_profile_image(): HasOne
    {
        return $this->hasOne(UserProfileImage::class);
    }

    public function password_requests(): HasMany
    {
        return $this->hasMany(PasswordRequest::class);
    }

    public function user_has_bonus(): HasMany
    {
        return $this->hasMany(UserHasBonus::class);
    }

    public function password_for_user(): HasOne
    {
        return $this->hasOne(PasswordForUser::class);
    }

    public function last_login(): HasMany
    {
        return $this->hasMany(LastLogin::class);
    }

    public function one_time_sms_passwords(): HasMany
    {
        return $this->hasMany(OneTimeSMSPassword::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function video_frameds(): HasMany
    {
        return $this->hasMany(VideoFramed::class);
    }

    public function company_branches(): MorphToMany
    {
        return $this->morphToMany(CompanyBranch::class, 'model', 'model_has_company_branches', 'model_id', 'company_branch_id')->withPivot('model_type')->wherePivot('model_type', User::class);
    }

    public function tokens(): MorphToMany
    {
        return $this->morphToMany(Token::class, 'model', 'model_has_tokens', 'model_id', 'token_id');
    }

    public function seen_infos() : HasMany
    {
        return $this->hasMany(SeenInfo::class);
    }

    // public function black_list(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         CaretakerBlackList::class,
    //         UserProfile::class,
    //         'user_id',
    //         'cbh_id_caretaker',
    //         'id',
    //         'id'
    //     );
    // }

    // public function family_recomenndations() : HasMany
    // {
    //     return $this->hasMany(FamilyRecommendation::class);
    // }
}
