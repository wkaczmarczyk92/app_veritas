<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMCaretakerPlanerData extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'caretakers_planer_data';
    protected $prefix = 'crt_';

    protected $primaryKey = 'crt_id_additional_data';
}
