<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_stock_in')) {
            return;
        }

        Schema::create('t_log_stock_in', function (Blueprint $table) {
            $table->increments('t_log_stock_in_id');
            $table->string('t_log_stock_in_child_part', 50);
            $table->string('t_log_stock_in_part_name', 50);
            $table->integer('t_log_stock_in_qty');
            $table->string('t_log_stock_in_pic', 50);
            $table->string('t_log_stock_in_grup', 50);
            $table->string('t_log_stock_in_delivery', 50);
            $table->timestamp('t_log_stock_in_timestamp')->useCurrent();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_stock_in');
    }
};
