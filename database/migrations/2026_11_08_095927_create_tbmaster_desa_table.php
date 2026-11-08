
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbmaster_desa', function (Blueprint $table) {
            $table->id('id_desa');

            $table->string('nama_desa', 150);
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            $table->string('provinsi', 100);
            $table->text('alamat_kantor')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('logo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbmaster_desa');
    }
};
