<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CRMCaretaker;

class CaretakerPlanerData extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'caretakers_planer_data';
    protected $prefix = 'crt_';

    protected $primaryKey = 'crt_id_addition_data';

    public function crm_profile(): BelongsTo
    {
        return $this->belongsTo(CRMCaretaker::class, 'crt_id_caretaker', 'crt_id_data_caretaker');
    }
}
