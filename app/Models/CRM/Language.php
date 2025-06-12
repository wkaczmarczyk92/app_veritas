<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'planer_language';
    protected $prefix = 'pll_';

    protected $primaryKey = 'pll_id';
}
