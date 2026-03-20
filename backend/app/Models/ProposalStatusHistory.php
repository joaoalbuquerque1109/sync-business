<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['proposal_id', 'from_status', 'to_status', 'changed_by', 'reason', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
