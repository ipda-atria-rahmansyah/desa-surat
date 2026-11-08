<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Surat extends Model
{
    protected $table = 'tbtr_surat';

    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'nomor_urut',
        'nomor_surat',
        'kategori_surat_id',
        'tahun_nomor',
        'jenis_surat_id',
        'warga_id',
        'user_id',
        'tanggal_surat',
        'keperluan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'nomor_urut' => 'integer',
            'tahun_nomor' => 'integer',
        ];
    }

    // Surat termasuk dalam satu kategori
    public function kategoriSurat(): BelongsTo
    {
        return $this->belongsTo(
            KategoriSurat::class,
            'kategori_surat_id',
            'id_kategori_surat'
        );
    }

    // Surat memiliki satu jenis surat
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(
            JenisSurat::class,
            'jenis_surat_id',
            'id_jenis_surat'
        );
    }

    // Surat dimiliki oleh satu warga
    public function warga(): BelongsTo
    {
        return $this->belongsTo(
            Warga::class,
            'warga_id',
            'id_warga'
        );
    }

    // Surat dibuat oleh satu pengguna
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id_user'
        );
    }

    // Surat memiliki satu detail
    public function detail(): HasOne
    {
        return $this->hasOne(
            SuratDetail::class,
            'surat_id',
            'id_surat'
        );
    }
}