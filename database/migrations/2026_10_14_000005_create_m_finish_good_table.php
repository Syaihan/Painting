<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_finish_good')) {
            return;
        }

        Schema::create('m_finish_good', function (Blueprint $table) {
            $table->string('barcode_fg', 15)->primary();
            $table->string('part_number_fg', 15);
            $table->string('fg_name', 50);

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_finish_good');
    }
};
