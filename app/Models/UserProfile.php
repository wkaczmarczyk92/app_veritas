<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'crt_id_caretaker'
    ];

    public function levels() : HasOne
    {
        return $this->hasOne(Level::class, 'id', 'level');
    }
}
