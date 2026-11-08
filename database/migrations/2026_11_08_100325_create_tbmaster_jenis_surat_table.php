
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbmaster_jenis_surat', function (Blueprint $table) {
            $table->id('id_jenis_surat');

            $table->foreignId('kategori_surat_id')
                ->constrained('tbmaster_kategori_surat', 'id_kategori_surat')
                ->restrictOnDelete();

            $table->string('nama_jenis', 150);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbmaster_jenis_surat');
    }
};
