<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractDPD extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'contracts_dpd';
    protected $prefix = 'cpd_';

    protected $primaryKey = 'cpd_id_contract';

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'con_id_contract', 'cpd_id_contract');
    }
}
