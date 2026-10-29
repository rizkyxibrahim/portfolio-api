<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'phone', 'email', 'profile_photo_path', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the profile photo URL.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (! $this->profile_photo_path) {
            return null;
        }

        return asset('storage/' . $this->profile_photo_path);
    }

    /**
     * Get the schedules owned by the user.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the todos owned by the user.
     */
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    /**
     * Get the reminders owned by the user.
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Get the finance transactions owned by the user.
     */
    public function financeTransactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    /**
     * Get the skills owned by the user.
     */
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * Get the projects owned by the user.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}