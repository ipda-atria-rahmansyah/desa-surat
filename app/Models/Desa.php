<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $table = 'tbmaster_desa';

    protected $primaryKey = 'id_desa';

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'alamat_kantor',
        'kode_pos',
        'logo',
    ];

    // Satu desa memiliki banyak warga
    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class, 'desa_id', 'id_desa');
    }
}
