<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('m_role_permission')) {
            return;
        }

        Schema::create('m_role_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');

            $table->index('role_id', 'fk_role_permission_role');
            $table->index('permission_id', 'fk_role_permission_permission');

            $table->foreign('permission_id', 'fk_role_permission_permission')
                ->references('id')->on('m_permissions')
                ->cascadeOnDelete();
            $table->foreign('role_id', 'fk_role_permission_role')
                ->references('id')->on('m_roles')
                ->cascadeOnDelete();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_role_permission');
    }
};
