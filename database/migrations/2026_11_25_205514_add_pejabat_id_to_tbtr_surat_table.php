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
        Schema::table('tbtr_surat', function (Blueprint $table) {
            $table->unsignedBigInteger('pejabat_id')->nullable()->after('user_id'); // sesuaikan tipe dengan id_pejabat
            $table->foreign('pejabat_id')
                ->references('id_pejabat')
                ->on('tbmaster_pejabat')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbtr_surat', function (Blueprint $table) {
            $table->dropForeign(['pejabat_id']);
            $table->dropColumn('pejabat_id');
        });
    }
};
