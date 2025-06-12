<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assignment extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'assignments';
    protected $prefix = 'ssg_';

    protected $primaryKey = 'ssg_id_assignment';

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'con_id_assignment', 'ssg_id_assignment');
    }
}
