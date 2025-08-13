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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->enum('day_type',['HOLIDAY','DAY'])->default('DAY');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->string('year');
            $table->date('date');
            $table->string('day')->nullable();
            $table->string('name')->nullable();
            $table->enum('status',['ACTIVE','DEACTIVE'])->default('ACTIVE');
            $table->unsignedBigInteger('holiday_type_id')->nullable();
            $table->foreign('holiday_type_id')->references('id')->on('holiday_types')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('hierarchies')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
