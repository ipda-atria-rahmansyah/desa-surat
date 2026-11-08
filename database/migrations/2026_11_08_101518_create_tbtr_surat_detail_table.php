
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbtr_surat_detail', function (Blueprint $table) {
            $table->id('id_surat_detail');

            $table->foreignId('surat_id')
                ->unique()
                ->constrained('tbtr_surat', 'id_surat')
                ->cascadeOnDelete();

            $table->json('field_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbtr_surat_detail');
    }
};
