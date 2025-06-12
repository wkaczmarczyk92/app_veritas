<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserProfile;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaretakerBlackList extends Model
{
    use HasFactory;

    protected $connection = 'crm_database';
    protected $table = 'caretakers_black_list_history';
    protected $prefix = 'cbh_';

    protected $primaryKey = 'cbh_id_history';

    // public function user_profile(): BelongsTo
    // {
    //     return $this->belongsTo(UserProfile::class, 'cbh_id_caretaker', 'crt_id_user_caretaker');
    // }
}
