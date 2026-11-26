<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model master pejabat yang berwenang menandatangani surat desa.
 *
 * Model ini memetakan tabel lama dengan primary key non-konvensional dan
 * menyediakan relasi arsip surat tanpa mengubah struktur database.
 */
class Pejabat extends Model
{
    use HasFactory;

    protected $table = 'tbmaster_pejabat';

    protected $primaryKey = 'id_pejabat';

    protected $fillable = [
        'nama_pejabat',
        'jabatan',
        'nip',
        'pangkat',
        'status_aktif',
    ];

    /**
     * Menjamin status dari database diperlakukan sebagai boolean di aplikasi.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Mengambil surat yang menunjuk pejabat ini sebagai penandatangan.
     *
     * @return HasMany<Surat, $this>
     */
    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class, 'pejabat_id', 'id_pejabat');
    }

    /**
     * Membatasi query hanya pada pejabat yang masih aktif.
     *
     * @param  Builder<Pejabat>  $query  Query pejabat.
     * @return Builder<Pejabat>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_aktif', true);
    }
}
