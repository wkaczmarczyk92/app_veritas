<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\UserProfile;
use App\Models\CRM\CaretakerPlanerData;
use App\Models\CRM\Contract;

class CRMCaretaker extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'caretakers';
    protected $prefix = 'crt_';

    protected $primaryKey = 'crt_id_caretaker';

    public function crm_assignments(): HasMany
    {
        return $this->hasMany(CRMAssignment::class, 'ssg_id_caretaker', 'crt_id_caretaker');
    }

    public function data(): HasOne
    {
        return $this->hasOne(CRMCaretakerPlanerData::class, 'crt_id_data_caretaker');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'crt_id_caretaker', 'crt_id_caretaker');
    }

    public function caretaker_planer_data(): HasOne
    {
        return $this->hasOne(CaretakerPlanerData::class, 'crt_id_data_caretaker', 'crt_id_caretaker');
    }

    // public function 

    // public function contract(): HasOne
    // {
    //     return $this->hasOne(Contract::class,)->latest();
    // }
}
