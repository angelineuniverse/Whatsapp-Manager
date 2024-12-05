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
        Schema::create('m_unit_type_tabs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('descriptions')->nullable();
            $table->bigInteger('price');
            $table->unsignedBigInteger('m_project_tabs_id');
            $table->unsignedBigInteger('m_unit_status_tabs_id')->nullable();
            $table->unsignedInteger('m_unit_class_tabs_id')->nullable();
            $table->integer('long_build')->default(0);
            $table->integer('long_land')->default(0);
            $table->integer('width_build')->default(0);
            $table->integer('width_land')->default(0);
            $table->timestamps();
            $table->foreign('m_project_tabs_id')->on('m_project_tabs')->references('id')->cascadeOnDelete();
            $table->foreign('m_unit_status_tabs_id')->on('m_unit_status_tabs')->references('id')->nullOnDelete();
            $table->foreign('m_unit_class_tabs_id')->on('m_unit_class_tabs')->references('id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_unit_type_tabs');
    }
};
