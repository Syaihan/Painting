<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_users')) {
            return;
        }

        Schema::create('m_users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->unsignedBigInteger('role_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('role_id', 'fk_operators_role');
            $table->foreign('role_id', 'fk_operators_role')
                ->references('id')->on('m_roles')
                ->nullOnDelete();

            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_users');
    }
};
