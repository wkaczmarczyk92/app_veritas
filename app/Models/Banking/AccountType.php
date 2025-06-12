<?php

namespace App\Models\Banking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type',
        'type_pl'
    ];

    public function bank_accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }
}
