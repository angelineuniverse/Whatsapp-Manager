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
        Schema::create('m_user_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_company_tabs_id')->nullable();
            $table->string('code');
            $table->string('email');
            $table->string('name');
            $table->string('password')->nullable();
            $table->string('contact')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedInteger('m_status_tabs_id');
            $table->timestamps();
            $table->foreign('m_company_tabs_id')->on('m_company_tabs')->references('id')->nullOnDelete();
            $table->foreign('m_status_tabs_id')->on('m_status_tabs')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user_tabs');
    }
};
