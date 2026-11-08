
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblog_activity_logs', function (Blueprint $table) {
            $table->id('id_activity_log');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('tbmaster_users', 'id_user')
                ->nullOnDelete();

            $table->enum('activity', [
                'CREATE',
                'UPDATE',
                'DELETE',
                'LOGIN',
                'LOGOUT',
            ]);

            $table->string('module', 100);
            $table->text('description')->nullable();

            // ID data yang terkena aktivitas
            // Tidak menggunakan foreign key agar log tetap tersimpan
            $table->unsignedBigInteger('record_id')->nullable();

            // Menyimpan perubahan data sebelum dan sesudah
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Log bersifat append-only
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblog_activity_logs');
    }
};
