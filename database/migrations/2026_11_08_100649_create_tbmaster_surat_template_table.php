
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbmaster_surat_template', function (Blueprint $table) {
            $table->id('id_template');

            $table->foreignId('jenis_surat_id')
                ->constrained('tbmaster_jenis_surat', 'id_jenis_surat')
                ->restrictOnDelete();

            $table->string('nama_template', 150);
            $table->string('file_template');
            $table->string('format', 20);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbmaster_surat_template');
    }
};
