<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'headline',
        'bio',
        'avatar',
        'resume_url',
        'location',
        'phone',
        'email',
        'website',
        'timezone',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class)->orderBy('display_order', 'desc');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('display_order', 'desc');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('display_order', 'asc');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class)->orderBy('display_order', 'asc');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('display_order', 'desc');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderBy('display_order', 'desc');
    }
}