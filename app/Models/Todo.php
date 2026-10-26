<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
        'scheduled_start_at',
        'scheduled_end_at',
        'due_at',
        'priority',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'scheduled_start_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
