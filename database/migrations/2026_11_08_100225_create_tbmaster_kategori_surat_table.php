<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbmaster_kategori_surat', function (Blueprint $table) {
            $table->id('id_kategori_surat');

            $table->string('nama_kategori', 100);
            $table->string('kode_nomor', 30)->unique();
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbmaster_kategori_surat');
    }
};
