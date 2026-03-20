<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'action', 'entity_type', 'entity_id', 'message', 'before_data', 'after_data', 'context', 'ip_address', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'context' => 'array',
        'created_at' => 'datetime',
    ];
}
