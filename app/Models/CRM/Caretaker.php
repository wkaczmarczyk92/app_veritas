<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\CRM\Contract;
use App\Models\CRM\Assignment;

use App\Models\CRMCaretakerPlanerData;

class Caretaker extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'caretakers';
    protected $prefix = 'crt_';

    protected $primaryKey = 'crt_id_caretaker';

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'ssg_id_caretaker', 'crt_id_caretaker');
    }

    public function data(): HasOne
    {
        return $this->hasOne(CRMCaretakerPlanerData::class, 'crt_id_data_caretaker');
    }

    public function business(): HasOne
    {
        return $this->hasOne(Business::class, 'bsn_id_caretaker', 'crt_id_caretaker');
    }

    // public function 

    // public function contract(): HasOne
    // {
    //     return $this->hasOne(Contract::class,)->latest();
    // }
}
