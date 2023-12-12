<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMPatientWakingUp extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'planer_waking_up';
    protected $prefix = 'fwu_';

    protected $primaryKey = 'fwu_id';
}
