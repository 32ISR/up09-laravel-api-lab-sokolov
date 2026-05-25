<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Ticket extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'status'];

    // В таблице tickets есть user_id → belongsTo
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // В таблице comments есть ticket_id → hasMany здесь
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}