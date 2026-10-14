<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_operator')) {
            return;
        }

        Schema::create('m_operator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 50)->nullable();
            $table->string('grup', 2)->nullable();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_operator');
    }
};
