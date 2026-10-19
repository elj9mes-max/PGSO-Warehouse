<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AccountLevel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'first_name',
    'middle_initial',
    'last_name',
    'username',
    'name',
    'email',
    'password',
    'account_level',
    'office_id',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'account_level' => AccountLevel::class,
            'office_id' => 'integer',
        ];
    }

    /**
     * Get the office that the user belongs to.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the requisition requests made by this user.
     */
    public function requisitionRequests(): HasMany
    {
        return $this->hasMany(RequisitionRequest::class);
    }

    /**
     * Determine if the user has an Admin account level.
     */
    public function isAdmin(): bool
    {
        return $this->account_level === AccountLevel::Admin;
    }

    /**
     * Determine if the user has a Staff account level.
     */
    public function isStaff(): bool
    {
        return $this->account_level === AccountLevel::Staff;
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        $mi = $this->middle_initial ? " {$this->middle_initial} " : ' ';

        return trim("{$this->first_name}{$mi}{$this->last_name}") ?: ($this->name ?? $this->username ?? '');
    }
}
