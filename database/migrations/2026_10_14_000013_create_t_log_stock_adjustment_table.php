<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_stock_adjustment')) {
            return;
        }

        Schema::create('t_log_stock_adjustment', function (Blueprint $table) {
            $table->unsignedBigInteger('t_log_stock_adjustment_id')->autoIncrement();
            $table->string('t_log_stock_adjustment_child_part', 15);
            $table->string('t_log_stock_adjustment_part_name', 255);
            $table->string('t_log_stock_adjustment_type', 50);
            $table->decimal('t_log_stock_adjustment_qty', 15, 2)->default(0);
            $table->decimal('t_log_stock_adjustment_stock_before', 15, 2)->default(0);
            $table->decimal('t_log_stock_adjustment_stock_after', 15, 2)->default(0);
            $table->text('t_log_stock_adjustment_reason')->nullable();
            $table->string('t_log_stock_adjustment_pic', 100)->nullable();
            $table->string('t_log_stock_adjustment_grup', 100)->nullable();
            $table->timestamp('t_log_stock_adjustment_timestamp')->useCurrent();

            $table->index('t_log_stock_adjustment_child_part', 'idx_child_part');
            $table->index('t_log_stock_adjustment_type', 'idx_type');
            $table->index('t_log_stock_adjustment_timestamp', 'idx_timestamp');

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_stock_adjustment');
    }
};
