<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class CRMCompany extends Model
{
    use HasFactory;

    
    protected $connection = 'crm_database';
    protected $table = 'company';
    protected $prefix = 'cmp_';

    protected $primaryKey = 'cmp_id';

    // public function crm_assignment() : HasOne
    // {
    //     return $this->belongsTo(CRMAssignment::class, 'ssg_id_caretaker', 'crt_id_caretaker');
    // }
}
