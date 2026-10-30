<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_setting_sealing', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('min_limit')->default(300)->comment('Batas minimum stock global');
            $table->unsignedInteger('max_limit')->default(500)->comment('Batas maksimum stock global');
            $table->unsignedInteger('cutting_qty')->default(100)->comment('Qty potong default saat scan reguler');
            $table->timestamps();

            $table->unique(['min_limit', 'max_limit', 'cutting_qty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_setting_sealing');
    }
};
