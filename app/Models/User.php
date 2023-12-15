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

use Spatie\Permission\Traits\HasRoles;

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

    public function user_profiles() : HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function user_points() : HasMany
    {
        return $this->hasMany(UserPoint::class);
    }

    public function ready_to_departure_dates() : HasOne
    {
        return $this->hasOne(ReadyToDepartureDate::class);
    }

    public function caretaker_recommendation() : HasMany
    {
        return $this->hasMany(CaretakerRecommendation::class);
    }

    public function family_recommendation() : HasMany
    {
        return $this->hasMany(FamilyRecommendation::class);
    }

    public function user_profile_image() : HasOne
    {
        return $this->hasOne(UserProfileImage::class);
    }

    public function password_requests() : HasMany
    {
        return $this->hasMany(PasswordRequest::class);
    }

    public function user_has_bonus() : HasMany
    {
        return $this->hasMany(UserHasBonus::class);
    }

    public function password_for_user() : HasOne
    {
        return $this->hasOne(PasswordForUser::class);
    }

    public function last_login() : HasMany
    {
        return $this->hasMany(LastLogin::class);
    }

    public function one_time_sms_passwords() : HasMany
    {
        return $this->hasMany(OneTimeSMSPassword::class);
    }

    public function offers() : HasMany
    {
        return $this->hasMany(Offer::class);
    }

    // public function family_recomenndations() : HasMany
    // {
    //     return $this->hasMany(FamilyRecommendation::class);
    // }
}
