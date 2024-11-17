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
        Schema::create('t_user_log_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_company_tabs_id');
            $table->unsignedBigInteger('m_user_tabs_id');
            $table->unsignedInteger('m_module_tabs_id');
            $table->unsignedInteger('m_action_tabs_id');
            $table->text('description');
            $table->timestamps();
            $table->foreign('m_company_tabs_id')->on('m_company_tabs')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_user_log_tabs');
    }
};
