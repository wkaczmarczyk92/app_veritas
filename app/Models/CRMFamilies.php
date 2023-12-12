<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CRMFamilies extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'families';
    protected $prefix = 'fml_';

    protected $primaryKey = 'fml_id_family';

    public function address() : HasOne
    {
        return $this->hasOne(CRMFamilyAddress::class, 'adr_id_address', 'fml_id_address');
    }

    public function planer() : HasMany
    {
        return $this->hasMany(CRMPlaner::class, 'pnr_id_family');
    }

    public function patient() : HasMany
    {
        return $this->hasMany(CRMPatient::class, 'fmp_id_family');
    }
    
}
