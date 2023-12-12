<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMProfileImage extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'caretakers_profiles_photos';
    protected $prefix = 'cph_';

    protected $primaryKey = 'cph_id_caretaker';
}
