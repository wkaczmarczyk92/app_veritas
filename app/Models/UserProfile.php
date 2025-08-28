<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\CRMCaretaker;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'level',
        'email',
        'phone_number',
        'recruiter_first_name',
        'recruiter_last_name',
        'crt_id_user_recruiter',
        'crt_id_caretaker',
        'user_id',
        'total_points',
        'current_points',
        'total_days'
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    protected $appends = ['full_name'];

    protected function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function levels(): HasOne
    {
        return $this->hasOne(Level::class, 'id', 'level');
    }

    public function crm_profile(): HasOne
    {
        return $this->hasOne(CRMCaretaker::class, 'crt_id_caretaker', 'crt_id_caretaker');
    }

    // public function black

    // public function contract(): HasMany
    // {
    //     // return $this->hasMany(Contract::class, )
    // }
}
