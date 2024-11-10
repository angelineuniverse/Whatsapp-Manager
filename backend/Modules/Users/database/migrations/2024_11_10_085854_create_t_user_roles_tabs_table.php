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
        Schema::create('t_user_roles_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_user_tabs_id');
            $table->unsignedBigInteger('m_roles_tabs_id');
            $table->foreign('m_user_tabs_id')->on('m_user_tabs')->references('id')->cascadeOnDelete();
            $table->foreign('m_roles_tabs_id')->on('m_roles_tabs')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_user_roles_tabs');
    }
};
