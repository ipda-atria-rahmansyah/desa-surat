<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenomoranSurat extends Model
{
    protected $table = 'tbtr_penomoran_surat';

    protected $primaryKey = 'id_penomoran';

    protected $fillable = [
        'kategori_surat_id',
        'tahun',
        'nomor_terakhir',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'nomor_terakhir' => 'integer',
        ];
    }

    // Penomoran dimiliki oleh satu kategori surat
    public function kategoriSurat(): BelongsTo
    {
        return $this->belongsTo(
            KategoriSurat::class,
            'kategori_surat_id',
            'id_kategori_surat'
        );
    }
}