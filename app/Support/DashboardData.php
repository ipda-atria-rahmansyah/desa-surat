<?php

namespace App\Support;

final class DashboardData
{
    public static function all(): array
    {
        return [
            'statistics' => [
                ['label' => 'Total Surat', 'value' => '128', 'note' => '+12% dari bulan lalu', 'icon' => 'bi-file-earmark-text-fill', 'tone' => 'green'],
                ['label' => 'Warga Terdaftar', 'value' => '1.245', 'note' => '+3% dari bulan lalu', 'icon' => 'bi-people-fill', 'tone' => 'blue'],
                ['label' => 'Kategori Surat', 'value' => '5', 'note' => 'Kategori tersedia', 'icon' => 'bi-folder-fill', 'tone' => 'orange'],
                ['label' => 'Jenis Surat', 'value' => '12', 'note' => 'Jenis surat tersedia', 'icon' => 'bi-file-earmark-text-fill', 'tone' => 'purple'],
            ],
            'letters' => [
                ['number' => '140/DS/2025', 'type' => 'Surat Keterangan Domisili', 'resident' => 'Budi Santoso', 'date' => '28 Okt 2025', 'status' => 'Selesai'],
                ['number' => '139/DS/2025', 'type' => 'Surat Keterangan Usaha', 'resident' => 'Siti Aminah', 'date' => '27 Okt 2025', 'status' => 'Selesai'],
                ['number' => '138/DS/2025', 'type' => 'Surat Keterangan Tidak Mampu', 'resident' => 'Ahmad Riyadi', 'date' => '27 Okt 2025', 'status' => 'Diproses'],
                ['number' => '137/DS/2025', 'type' => 'Surat Keterangan Kelahiran', 'resident' => 'Rina Wulandari', 'date' => '26 Okt 2025', 'status' => 'Selesai'],
                ['number' => '136/DS/2025', 'type' => 'Surat Pengantar SKCK', 'resident' => 'Dedi Kurniawan', 'date' => '25 Okt 2025', 'status' => 'Selesai'],
            ],
            'categories' => [
                ['name' => 'Kependudukan', 'value' => 62, 'tone' => 'people'],
                ['name' => 'Usaha', 'value' => 28, 'tone' => 'business'],
                ['name' => 'Kesejahteraan', 'value' => 18, 'tone' => 'welfare'],
                ['name' => 'Lainnya', 'value' => 20, 'tone' => 'other'],
            ],
        ];
    }
}
