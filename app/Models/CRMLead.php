<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMLead extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'leads';
    protected $prefix = 'ldl_';

    protected $primaryKey = 'ldl_id_lead';
}
