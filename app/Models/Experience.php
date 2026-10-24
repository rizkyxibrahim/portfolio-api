<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'position',
        'employment_type',
        'location',
        'description',
        'responsibilities',
        'start_date',
        'end_date',
        'is_current',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'responsibilities' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
