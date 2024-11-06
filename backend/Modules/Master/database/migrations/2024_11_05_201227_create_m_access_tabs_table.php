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
        Schema::create('m_access_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedBigInteger('m_company_tabs_id');
            $table->string('title');
            $table->string('color');
            $table->foreign('m_company_tabs_id')->on('m_company_tabs')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_access_tabs');
    }
};
