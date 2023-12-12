<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMRecruiter extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'users';
    protected $prefix = 'usr_';

    protected $primaryKey = 'usr_id_user';

}
