<?php

namespace App\Models\Banking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Http\Requests\BOKRequest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_name',
        'account_type_id',
        'account_number',
        'bank_name',
        'swift'
    ];

    public function bok_request(): BelongsToMany
    {
        return $this->belongsToMany(
            BOKRequest::class,
            'bok_request_has_bank_account',
            'bank_account_id',
            'bok_request_id'
        );
    }

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }
}
