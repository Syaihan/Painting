<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_jam_produksi')) {
            return;
        }

        Schema::create('m_jam_produksi', function (Blueprint $table) {
            $table->increments('m_jam_produksi_id');
            $table->string('m_jam_produksi_shift', 50);
            $table->string('m_jam_produksi_ket', 50);
            $table->time('m_jam_produksi_jam_mulai');
            $table->time('m_jam_produksi_jam_selesai');
            $table->unsignedTinyInteger('m_jam_produksi_lintas_hari')->default(0);
            $table->timestamp('m_jam_produksi_timestamp')->useCurrent();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_jam_produksi');
    }
};
