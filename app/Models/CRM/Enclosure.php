<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enclosure extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'enclosure';
    protected $prefix = 'enc_';

    protected $primaryKey = 'enc_id_enclosure';

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'enc_id_contract', 'con_id_contract');
    }
}
