<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('t_log_session_history')) {
            return;
        }

        Schema::create('t_log_session_history', function (Blueprint $table) {
            $table->increments('t_log_session_history_id');
            $table->string('t_log_session_history_username', 50);
            $table->string('t_log_session_history_name', 100);
            $table->string('t_log_session_history_action', 10);
            $table->string('t_log_session_history_ip', 45)->nullable();
            $table->string('t_log_session_history_browser', 255)->nullable();
            $table->timestamp('t_log_session_history_timestamp')->useCurrent();

            $table->index('t_log_session_history_username', 'idx_session_username');
            $table->index('t_log_session_history_timestamp', 'idx_session_timestamp');


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_log_session_history');
    }
};
