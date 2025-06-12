<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractTo extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'contract_to';
    protected $prefix = 'cnt_';

    protected $primaryKey = 'cnt_id_contract_to';

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'con_id_contract_to', 'cnt_id_contract_to');
    }
}
