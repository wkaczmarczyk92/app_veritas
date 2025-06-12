<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class A1 extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'A1';
    protected $prefix = 'one_';

    protected $primaryKey = 'one_id_a1';

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'con_id_contract', 'one_id_contract');
    }

    public function ekuz(): HasOne
    {
        return $this->hasOne(EKUZ::class, 'ekz_id_a1', 'one_id_a1');
    }
}
