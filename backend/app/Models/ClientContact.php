<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientContact extends Model
{
    protected $fillable = ['client_id', 'name', 'role', 'email', 'phone', 'mobile', 'decision_maker', 'notes'];

    protected $casts = ['decision_maker' => 'bool'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
