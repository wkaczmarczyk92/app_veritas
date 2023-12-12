<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class CRMPatient extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'families_patients';
    protected $prefix = 'fmp_';

    protected $primaryKey = 'cmp_id_patient';

    public function mobility() : HasOne
    {
        return $this->hasOne(CRMPatientMobility::class, 'plm_id', 'fmp_id_mobility');
    }

    public function waking_up() : HasOne
    {
        return $this->hasOne(CRMPatientWakingUp::class, 'fwu_id', 'fmp_id_waking_up');
    }

}
