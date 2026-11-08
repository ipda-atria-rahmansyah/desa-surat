<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbmaster_users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Warga yang pertama kali diinput oleh user
    public function wargaDibuat(): HasMany
    {
        return $this->hasMany(Warga::class, 'create_by', 'id_user');
    }

    // Warga yang terakhir diubah oleh user
    public function wargaDiubah(): HasMany
    {
        return $this->hasMany(Warga::class, 'modify_by', 'id_user');
    }

    // Surat yang dibuat oleh user
    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class, 'user_id', 'id_user');
    }

    // Log aktivitas user
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'id_user');
    }
}
