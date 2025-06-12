<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EKUZ extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'EKUZ';
    protected $prefix = 'ekz_';

    protected $primaryKey = 'ekz_id_ekuz';

    public function a1(): BelongsTo
    {
        return $this->belongsTo(A1::class, 'ekz_id_a1', 'one_id_a1');
    }
}
