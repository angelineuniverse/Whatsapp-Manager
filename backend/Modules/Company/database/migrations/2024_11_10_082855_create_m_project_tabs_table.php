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
        Schema::create('m_project_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_company_tabs_id');
            $table->string('title');
            $table->string('avatar')->nullable();
            $table->string('descriptions')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('m_status_tabs_id')->default(3)->nullable();
            $table->timestamps();
            $table->foreign('m_company_tabs_id')->on('m_company_tabs')->references('id')->cascadeOnDelete();
            $table->foreign('m_status_tabs_id')->on('m_status_tabs')->references('id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_project_tabs');
    }
};
