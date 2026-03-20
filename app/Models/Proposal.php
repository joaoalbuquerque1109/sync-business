<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    protected $fillable = [
        'number', 'client_id', 'responsible_user_id', 'proposal_template_id', 'status', 'issue_date', 'valid_until',
        'title', 'general_notes', 'subtotal_amount', 'discount_amount', 'total_amount', 'currency', 'source',
        'created_by', 'updated_by', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ProposalTemplate::class, 'proposal_template_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProposalItem::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProposalVersion::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ProposalStatusHistory::class);
    }
}
