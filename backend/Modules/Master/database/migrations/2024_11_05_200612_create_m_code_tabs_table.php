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
        Schema::create('m_code_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('preffix');
            $table->integer('start');
            $table->tinyInteger('length');
            $table->smallInteger('year');
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_code_tabs');
    }
};
