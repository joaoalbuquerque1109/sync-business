<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAddress extends Model
{
    protected $fillable = ['client_id', 'type', 'street', 'number', 'complement', 'district', 'city', 'state', 'zip_code', 'is_primary'];

    protected $casts = ['is_primary' => 'bool'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
