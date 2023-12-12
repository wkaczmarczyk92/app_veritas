<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CRMAssignment extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'assignments';
    protected $prefix = 'ssg_';

    protected $primaryKey = 'ssg_id_assignment';

    // public function crm_caretaker() : BelongsTo
    // {
    //     return $this->belongsTo(CRMCaretaker::class, 'ssg_id_caretaker', 'crt_id_caretaker');
    // }

    public function crm_company() : HasOne
    {
        return $this->hasOne(CRMCompany::class, 'cmp_id_company', 'ssg_id_company');
    }
}
