<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_scan')) {
            return;
        }

        Schema::create('t_log_scan', function (Blueprint $table) {
            $table->increments('t_log_scan_id');
            $table->string('t_log_scan_fg', 15);
            $table->string('t_log_scan_fg_part_number', 15);
            $table->integer('t_log_scan_qty');
            $table->string('t_log_scan_operator', 50);
            $table->string('t_log_scan_pic', 50);
            $table->string('t_log_scan_grup', 2);
            $table->timestamp('t_log_scan_timestamp')->useCurrent();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_scan');
    }
};
