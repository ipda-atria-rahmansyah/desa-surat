    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan status aktif warga untuk membedakan warga aktif tanpa
     * menghapus warga yang masih dirujuk arsip surat.
     */
    public function up(): void
    {
        Schema::table('tbmaster_warga', function (Blueprint $table): void {
            // Data lama dianggap aktif karena tidak memiliki riwayat status.
            $table->boolean('is_active')->default(true)->after('desa_id');
            $table->index(['desa_id', 'is_active'], 'idx_warga_desa_active');
        });
    }

    /**
     * Menghapus status dan index jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('tbmaster_warga', function (Blueprint $table): void {
            $table->dropIndex('idx_warga_desa_active');
            $table->dropColumn('is_active');
        });
    }
};
