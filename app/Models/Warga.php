<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $table = 'tbmaster_warga';

    protected $primaryKey = 'id_warga';

    protected $fillable = [
        'nama_lengkap',
        'no_kk',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'kedusunan',
        'shdk',
        'agama',
        'status_perkawinan',
        'pendidikan_terakhir',
        'pekerjaan',
        'nama_ibu',
        'nama_ayah',
        'desa_id',
        'create_by',
        'modify_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    // Warga berasal dari satu desa
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'id_desa');
    }

    // User yang pertama kali menginput warga
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by', 'id_user');
    }

    // User yang terakhir mengubah data warga
    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modify_by', 'id_user');
    }

    // Satu warga dapat memiliki banyak surat
    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class, 'warga_id', 'id_warga');
    }
}
