<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['nama', 'username', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }

    public function dataProduksi(): HasMany
    {
        return $this->hasMany(DataProduksi::class, 'created_by');
    }

    public function checksheets(): HasMany
    {
        return $this->hasMany(Checksheet::class);
    }
}
