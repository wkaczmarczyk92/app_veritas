<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMFamilyAddress extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'addresses';
    protected $prefix = 'adr_';

    protected $primaryKey = 'adr_id_address';
}
