<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratDetail extends Model
{
    protected $table = 'tbtr_surat_detail';

    protected $primaryKey = 'id_surat_detail';

    protected $fillable = [
        'surat_id',
        'field_data',
    ];

    protected function casts(): array
    {
        return [
            'field_data' => 'array',
        ];
    }

    // Detail dimiliki oleh satu surat
    public function surat(): BelongsTo
    {
        return $this->belongsTo(
            Surat::class,
            'surat_id',
            'id_surat'
        );
    }
}