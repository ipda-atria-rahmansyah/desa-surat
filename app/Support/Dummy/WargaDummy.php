<?php

namespace App\Support\Dummy;

final class WargaDummy
{
    /**
     * @return array<int, array{no: int, nik: string, nama: string, alamat: string}>
     */
    public static function all(): array
    {
        return [
            ['no' => 1, 'nik' => '3201010101010001', 'nama' => 'Ahmad Fauzi', 'alamat' => 'Jl. Melati No. 12, Gunajaya'],
            ['no' => 2, 'nik' => '3201010101010002', 'nama' => 'Siti Aminah', 'alamat' => 'Kp. Cibatu RT 01/ RW 01, Gunajaya'],
            ['no' => 3, 'nik' => '3201010101010003', 'nama' => 'Budi Santoso', 'alamat' => 'Jl. Raya Desa No. 45, Gunajaya'],
            ['no' => 4, 'nik' => '3201010101010004', 'nama' => 'Rina Marlina', 'alamat' => 'Kp. Sukamaju RT 02/ RW 03, Gunajaya'],
            ['no' => 5, 'nik' => '3201010101010005', 'nama' => 'Dedi Kurniawan', 'alamat' => 'Jl. Cisadane No. 78, Gunajaya'],
        ];
    }
}
