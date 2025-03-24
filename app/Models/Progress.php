<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{
    protected $fillable = [
        'target_id',
        'value',
        'tracked_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }
}
