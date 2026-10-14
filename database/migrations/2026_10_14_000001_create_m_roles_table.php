<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_roles')) {
            return;
        }

        Schema::create('m_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('name', 50)->unique();
            $table->string('display_name', 100);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_roles');
    }
};
