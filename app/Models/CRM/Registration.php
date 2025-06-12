<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'registrations';
    protected $prefix = 'reg_';

    protected $primaryKey = 'reg_id_registration';

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'con_id_contract', 'reg_id_contract');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'reg_id_company');
    }
}
