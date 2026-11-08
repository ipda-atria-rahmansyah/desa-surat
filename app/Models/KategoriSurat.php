<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSurat extends Model
{
    protected $table = 'tbmaster_kategori_surat';

    protected $primaryKey = 'id_kategori_surat';

    protected $fillable = [
        'nama_kategori',
        'kode_nomor',
        'deskripsi',
    ];

    // Satu kategori memiliki banyak jenis surat
    public function jenisSurat(): HasMany
    {
        return $this->hasMany(
            JenisSurat::class,
            'kategori_surat_id',
            'id_kategori_surat'
        );
    }

    // Satu kategori memiliki banyak surat yang diterbitkan
    public function surat(): HasMany
    {
        return $this->hasMany(
            Surat::class,
            'kategori_surat_id',
            'id_kategori_surat'
        );
    }

    // Satu kategori memiliki banyak data penomoran
    public function penomoranSurat(): HasMany
    {
        return $this->hasMany(
            PenomoranSurat::class,
            'kategori_surat_id',
            'id_kategori_surat'
        );
    }
}