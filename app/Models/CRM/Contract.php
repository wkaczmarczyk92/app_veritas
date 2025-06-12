<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Contract extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'contracts';
    protected $prefix = 'con_';

    protected $primaryKey = 'con_id_contract';

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'con_id_contract', 'reg_id_contract');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'con_id_assignment', 'ssg_id_assignment');
    }

    public function contract_to(): BelongsTo
    {
        return $this->belongsTo(ContractTo::class, 'con_id_contract_to', 'cnt_id_contract_to');
    }

    public function dpd(): HasOne
    {
        return $this->hasOne(ContractDPD::class, 'cpd_id_contract', 'con_id_contract');
    }

    public function a1(): HasOne
    {
        return $this->hasOne(A1::class, 'one_id_contract', 'con_id_contract');
    }

    public function enclosure(): HasOne
    {
        return $this->hasOne(Enclosure::class, 'enc_id_contract', 'con_id_contract');
    }
}
