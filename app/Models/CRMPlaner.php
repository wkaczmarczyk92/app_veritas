<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CRMPlaner extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'planer';
    protected $prefix = 'pnr_';

    protected $primaryKey = 'pnr_id_planer';

    public function family() : BelongsTo
    {
        return $this->belongsTo(CRMFamilies::class, 'pnr_id_family');
    }
}
