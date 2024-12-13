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
        Schema::create('m_menu_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->tinyInteger('m_status_tabs_id')->default(1)->comment('2 = not active, 1 = active');
            $table->mediumInteger('sequence');
            $table->integer('parent_id')->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_menu_tabs');
    }
};
