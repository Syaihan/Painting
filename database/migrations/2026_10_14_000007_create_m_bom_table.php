<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_bom')) {
            return;
        }

        Schema::create('m_bom', function (Blueprint $table) {
            $table->string('barcode_fg', 15);
            $table->string('part_number_fg', 15);
            $table->string('part_number_child_part', 15);
            $table->integer('bom_qty');

            $table->index('barcode_fg', 'barcode_fg');
            $table->index('part_number_child_part', 'part_number_child_part');

            $table->foreign('part_number_child_part', 'fk_bom_child_part')
                ->references('part_number_child_part')->on('m_child_part')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('barcode_fg', 'fk_bom_finish_good')
                ->references('barcode_fg')->on('m_finish_good')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_bom');
    }
};
