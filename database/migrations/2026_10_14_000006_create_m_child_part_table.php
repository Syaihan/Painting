<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_child_part')) {
            return;
        }

        Schema::create('m_child_part', function (Blueprint $table) {
            $table->string('part_number_child_part', 15)->primary();
            $table->string('material_name', 50);
            $table->integer('child_part_qty');

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_child_part');
    }
};
