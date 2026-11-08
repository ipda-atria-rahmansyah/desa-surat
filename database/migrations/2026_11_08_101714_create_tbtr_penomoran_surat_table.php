
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbtr_penomoran_surat', function (Blueprint $table) {
            $table->id('id_penomoran');

            $table->foreignId('kategori_surat_id')
                ->constrained('tbmaster_kategori_surat', 'id_kategori_surat')
                ->restrictOnDelete();

            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('nomor_terakhir')->default(0);

            $table->unique(
                ['kategori_surat_id', 'tahun'],
                'uq_penomoran_kategori_tahun'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbtr_penomoran_surat');
    }
};
