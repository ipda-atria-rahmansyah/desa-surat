<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratTemplate extends Model
{
    protected $table = 'tbmaster_surat_template';

    protected $primaryKey = 'id_template';

    protected $fillable = [
        'jenis_surat_id',
        'nama_template',
        'file_template',
        'format',
    ];

    // Template dimiliki oleh satu jenis surat
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(
            JenisSurat::class,
            'jenis_surat_id',
            'id_jenis_surat'
        );
    }
}