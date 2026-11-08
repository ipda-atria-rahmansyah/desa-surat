
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbtr_surat', function (Blueprint $table) {
            $table->id('id_surat');

            // Penomoran surat
            $table->unsignedInteger('nomor_urut');
            $table->string('nomor_surat', 100)->unique();
            $table->unsignedInteger('tahun_nomor');

            // Relasi kategori dan jenis surat
            $table->foreignId('kategori_surat_id')
                ->constrained('tbmaster_kategori_surat', 'id_kategori_surat')
                ->restrictOnDelete();

            $table->foreignId('jenis_surat_id')
                ->constrained('tbmaster_jenis_surat', 'id_jenis_surat')
                ->restrictOnDelete();

            // Relasi warga dan petugas
            $table->foreignId('warga_id')
                ->constrained('tbmaster_warga', 'id_warga')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('tbmaster_users', 'id_user')
                ->restrictOnDelete();

            // Informasi surat
            $table->date('tanggal_surat');
            $table->text('keperluan')->nullable();

            $table->enum('status', [
                'draft',
                'selesai',
                'dibatalkan',
            ])->default('draft');

            // Mencegah nomor urut ganda dalam kategori dan tahun yang sama
            $table->unique(
                ['kategori_surat_id', 'tahun_nomor', 'nomor_urut'],
                'uq_nomor_surat_kategori_tahun'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbtr_surat');
    }
};
