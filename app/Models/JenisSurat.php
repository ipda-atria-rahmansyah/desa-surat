<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    protected $table = 'tbmaster_jenis_surat';

    protected $primaryKey = 'id_jenis_surat';

    protected $fillable = [
        'kategori_surat_id',
        'nama_jenis',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Jenis surat termasuk ke dalam satu kategori
    public function kategoriSurat(): BelongsTo
    {
        return $this->belongsTo(
            KategoriSurat::class,
            'kategori_surat_id',
            'id_kategori_surat'
        );
    }

    // Satu jenis surat dapat memiliki banyak template
    public function template(): HasMany
    {
        return $this->hasMany(
            SuratTemplate::class,
            'jenis_surat_id',
            'id_jenis_surat'
        );
    }

    // Satu jenis surat dapat digunakan pada banyak surat
    public function surat(): HasMany
    {
        return $this->hasMany(
            Surat::class,
            'jenis_surat_id',
            'id_jenis_surat'
        );
    }
}