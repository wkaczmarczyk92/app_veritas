<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'company';
    protected $prefix = 'cmp_';

    protected $primaryKey = 'cmp_id_company';

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'reg_id_company');
    }
}
