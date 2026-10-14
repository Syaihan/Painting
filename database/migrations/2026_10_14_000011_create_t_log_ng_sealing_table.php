<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_ng_sealing')) {
            return;
        }

        Schema::create('t_log_ng_sealing', function (Blueprint $table) {
            $table->increments('t_log_ng_sealing_id');
            $table->string('t_log_ng_sealing_child_part', 15)->nullable();
            $table->string('t_log_ng_sealing_part_name', 50)->nullable();
            $table->integer('t_log_ng_sealing_qty')->nullable();
            $table->string('t_log_ng_sealing_ket', 50)->nullable();
            $table->string('t_log_ng_sealing_operator', 50)->nullable();
            $table->string('t_log_ng_sealing_grup', 2)->nullable();
            $table->timestamp('t_log_ng_sealing_timestamp')->nullable()->useCurrent();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_ng_sealing');
    }
};
