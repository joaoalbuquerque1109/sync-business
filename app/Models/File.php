<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    public $timestamps = false;

    protected $fillable = ['attachable_type', 'attachable_id', 'disk', 'path', 'original_name', 'mime_type', 'extension', 'size', 'category', 'uploaded_by', 'created_at'];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
