<?php

namespace App\Models;

use App\Enums\TargetType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Target extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_value',
        'target_type',
        'user_id',
    ];

    protected $casts = [
        'target_type' => TargetType::class,
        'target_value' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function latestProgress()
    {
        return $this->hasOne(Progress::class)->latestOfMany('tracked_at');
    }
}
