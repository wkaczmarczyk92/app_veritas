<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Banking\BankAccount;

class BOKRequest extends Model
{
    use HasFactory;

    protected $table = 'bok_requests';
    protected $fillable = [
        'user_id',
        'subject_id',
        'msg'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): HasOne
    {
        return $this->hasOne(BOKSubject::class, 'id', 'subject_id');
    }

    public function bank_account(): BelongsToMany
    {
        return $this->belongsToMany(
            BankAccount::class,
            'bok_request_has_bank_account',
            'bok_request_id',
            'bank_account_id'
        );
    }
}
