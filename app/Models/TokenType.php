<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Token;

class TokenType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
