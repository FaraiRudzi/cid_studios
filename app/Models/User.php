<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    public function getFilamentName(): string
    {
        return trim(($this->first_name ?? '').' '.($this->surname ?? '')) ?: ($this->email ?? 'User');
    }

    protected $fillable = [
        'force_number',
        'first_name',
        'surname',
        'email',
        'phone_number',
        'role',
        'password',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function getNameAttribute(): string
    {
        return trim((string) ($this->first_name ?? '').' '.(string) ($this->surname ?? ''));
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isPhotographer(): bool
    {
        return $this->role === 'PHOTOGRAPHER';
    }

    public function assignedCases(): HasMany
    {
        return $this->hasMany(CaseModel::class, 'photographer_id');
    }
}
