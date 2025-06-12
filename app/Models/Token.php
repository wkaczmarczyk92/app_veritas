<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\TokenType;

class Token extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'token_type_id'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(TokenType::class);
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_token', 'token_id', 'model_id');
    }
}
