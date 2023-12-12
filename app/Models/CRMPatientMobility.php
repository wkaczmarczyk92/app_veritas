<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMPatientMobility extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'planer_mobility';
    protected $prefix = 'plm_';

    protected $primaryKey = 'plm_id';

}
