<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_sealing_plan')) {
            return;
        }

        Schema::create('t_log_sealing_plan', function (Blueprint $table) {
            $table->bigIncrements('t_log_sealing_plan_id');
            $table->date('t_log_sealing_plan_date')->comment('Tanggal rencana sealing');
            $table->string('t_log_sealing_plan_shift_name', 50)->comment('Nama shift aktif');
            $table->string('t_log_sealing_plan_barcode_fg', 15)->comment('FK ke m_finish_good.barcode_fg');
            $table->unsignedInteger('t_log_sealing_plan_planned_qty')->default(0)->comment('Target jumlah produksi');
            $table->unsignedInteger('t_log_sealing_plan_actual_qty')->default(0)->comment('Hasil aktual produksi');
            $table->string('t_log_sealing_plan_status', 20)->default('planned')->comment('planned/completed/cancelled');
            $table->string('t_log_sealing_plan_created_by', 50)->nullable()->comment('Operator yang membuat rencana');
            $table->timestamp('t_log_sealing_plan_created_at')->useCurrent();
            $table->timestamp('t_log_sealing_plan_updated_at')->useCurrent();

            $table->unique(['t_log_sealing_plan_date', 't_log_sealing_plan_shift_name', 't_log_sealing_plan_barcode_fg']);
            $table->foreign('t_log_sealing_plan_barcode_fg')
                ->references('barcode_fg')
                ->on('m_finish_good')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_sealing_plan');
    }
};