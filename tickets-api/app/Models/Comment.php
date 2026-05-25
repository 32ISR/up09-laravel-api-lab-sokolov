<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'body'];

    // В таблице comments есть ticket_id → belongsTo
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    // В таблице comments есть user_id → ещё один belongsTo
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}