<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbmaster_pejabat', function (Blueprint $table) {
            $table->id('id_pejabat');

            $table->string('nama_pejabat', 150);
            $table->string('jabatan', 100);
            $table->string('nip', 30)->nullable();
            $table->string('pangkat', 100)->nullable();

            $table->boolean('status_aktif')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbmaster_pejabat');
    }
};
