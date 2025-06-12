<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'contracts';
    protected $prefix = 'con_';

    protected $primaryKey = 'con_id_contract';
}
