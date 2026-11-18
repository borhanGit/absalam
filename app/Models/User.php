<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'phone',
        'warehouse_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'is_active' => 'boolean',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Role verification helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isStorekeeper(): bool
    {
        return $this->role === 'storekeeper';
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function canAccessFinancials(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function roleName(): string
    {
        $key = "roles.{$this->role}";
        $trans = __($key);
        return $trans !== $key ? $trans : $this->roleNameBengali();
    }

    public function roleNameBengali(): string
    {
        return match ($this->role) {
            'admin' => 'মালিক / সুপার অ্যাডমিন (Admin)',
            'manager' => 'শপ ম্যানেজার (Manager)',
            'cashier' => 'কাউন্টার ক্যাশিয়ার (Cashier)',
            'storekeeper' => 'গোডাউন ইন-চার্জ (Storekeeper)',
            default => ucfirst($this->role ?? 'ক্যাশিয়ার'),
        };
    }

    public function roleBadgeClass(): string
    {
        return match ($this->role) {
            'admin' => 'bg-purple-500/15 text-purple-300 border-purple-500/30',
            'manager' => 'bg-cyan-500/15 text-cyan-300 border-cyan-500/30',
            'cashier' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
            'storekeeper' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
            default => 'bg-slate-700 text-slate-300 border-slate-600',
        };
    }
}
