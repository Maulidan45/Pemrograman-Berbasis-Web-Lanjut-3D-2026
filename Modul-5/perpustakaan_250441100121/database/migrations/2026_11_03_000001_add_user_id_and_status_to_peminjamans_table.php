<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('status')->default('menunggu')->after('tanggal_kembali');

            $table->timestamp('disetujui_pada')->nullable()->after('status');
            $table->timestamp('dikembalikan_pada')->nullable()->after('disetujui_pada');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'status',
                'disetujui_pada',
                'dikembalikan_pada',
            ]);
        });
    }
};
