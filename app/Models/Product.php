<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'external_code', 'code', 'name', 'description', 'category', 'unit', 'base_price', 'status',
        'technical_notes', 'source', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    public function proposalItems(): HasMany
    {
        return $this->hasMany(ProposalItem::class);
    }
}
