
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbmaster_warga', function (Blueprint $table) {
            $table->id('id_warga');

            // Identitas warga
            $table->string('nama_lengkap', 150);
            $table->string('no_kk', 16)->nullable();
            $table->string('nik', 16)->unique();

            // Data kelahiran
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();

            // Data pribadi
            $table->string('jenis_kelamin', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kedusunan', 100)->nullable();
            $table->string('shdk', 50)->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('status_perkawinan', 50)->nullable();
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->string('pekerjaan', 100)->nullable();

            // Data orang tua
            $table->string('nama_ibu', 150)->nullable();
            $table->string('nama_ayah', 150)->nullable();

            // Relasi desa
            $table->foreignId('desa_id')
                ->constrained('tbmaster_desa', 'id_desa')
                ->restrictOnDelete();

            // Riwayat input dan perubahan data
            $table->foreignId('create_by')
                ->constrained('tbmaster_users', 'id_user')
                ->restrictOnDelete();

            $table->foreignId('modify_by')
                ->nullable()
                ->constrained('tbmaster_users', 'id_user')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbmaster_warga');
    }
};
