<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'external_code', 'cnpj', 'company_name', 'trade_name', 'email', 'phone', 'primary_contact_name',
        'billing_range', 'state', 'city', 'zip_code', 'microregion', 'notes', 'source', 'is_active',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(ClientAddress::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
