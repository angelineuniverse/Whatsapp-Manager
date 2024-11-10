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
        Schema::create('m_roles_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_project_tabs_id');
            $table->integer('parent_id');
            $table->string('title');
            $table->string('color');
            $table->integer('sequence')->default(1);
            $table->foreign('m_project_tabs_id')->on('m_project_tabs')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_roles_tabs');
    }
};
