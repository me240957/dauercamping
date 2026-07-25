<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'aktiv',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'aktiv'             => 'boolean',
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerwalter(): bool
    {
        return in_array($this->role, ['admin', 'verwalter']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin'     => 'Administrator',
            'verwalter' => 'Verwalter',
            'leser'     => 'Leser',
            default     => $this->role,
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin'     => 'bg-red-100 text-red-800',
            'verwalter' => 'bg-blue-100 text-blue-800',
            'leser'     => 'bg-gray-100 text-gray-700',
            default     => 'bg-gray-100 text-gray-700',
        };
    }
}
