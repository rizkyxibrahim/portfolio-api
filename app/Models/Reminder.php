<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'reminder_at',
        'is_sent',
        'is_completed',
        'recurring',
        'recurring_pattern',
        'delivery_status',
        'retry_count',
        'max_retries',
        'dedupe_key',
    ];

    protected $casts = [
        'reminder_at' => 'datetime',
        'is_sent' => 'boolean',
        'is_completed' => 'boolean',
        'recurring' => 'boolean',
        'delivery_status' => 'string',
        'retry_count' => 'integer',
        'max_retries' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
