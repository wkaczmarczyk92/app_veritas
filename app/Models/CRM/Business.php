<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Business extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'business';
    protected $prefix = 'bsn_';

    protected $primaryKey = 'bsn_id_business';

    public function caretaker(): BelongsTo
    {
        return $this->belongsTo(Caretaker::class, 'bsn_id_caretaker', 'crt_id_caretaker');
    }
}
