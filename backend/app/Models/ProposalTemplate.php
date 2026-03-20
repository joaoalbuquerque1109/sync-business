<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalTemplate extends Model
{
    protected $fillable = ['name', 'code', 'version', 'description', 'excel_file_path', 'mapping_config', 'is_default', 'is_active'];

    protected $casts = [
        'mapping_config' => 'array',
        'is_default' => 'bool',
        'is_active' => 'bool',
    ];

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
