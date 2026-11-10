<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_sealing_plan_update_log')) {
            return;
        }

        Schema::create('t_log_sealing_plan_update_log', function (Blueprint $table) {
            $table->bigIncrements('t_log_sealing_plan_update_log_id');
            $table->date('t_log_sealing_plan_update_log_date')->comment('Tanggal rencana');
            $table->string('t_log_sealing_plan_update_log_shift_name', 50)->comment('Nama shift');
            $table->string('t_log_sealing_plan_update_log_barcode_fg', 15)->comment('FK ke m_finish_good');
            $table->string('t_log_sealing_plan_update_log_part_number_fg', 15)->comment('Part number FG');
            $table->string('t_log_sealing_plan_update_log_fg_name', 50)->comment('Nama Finish Good');
            $table->integer('t_log_sealing_plan_update_log_old_qty')->comment('Qty sebelum diupdate');
            $table->integer('t_log_sealing_plan_update_log_new_qty')->comment('Qty setelah diupdate');
            $table->string('t_log_sealing_plan_update_log_created_by', 50)->comment('Operator yang melakukan update');
            $table->timestamp('t_log_sealing_plan_update_log_created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_sealing_plan_update_log');
    }
};