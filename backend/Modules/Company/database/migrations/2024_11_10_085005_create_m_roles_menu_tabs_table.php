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
        Schema::create('m_roles_menu_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_roles_tabs_id');
            $table->unsignedInteger('m_menu_tabs_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_roles_menu_tabs');
    }
};
