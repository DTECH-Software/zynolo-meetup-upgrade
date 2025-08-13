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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_details_id')->nullable();
            $table->foreign('leave_details_id')->references('id')->on('leave_details');
            $table->enum('status',['REQUESTED','APPROVED','REJECTED','CANCELLED'])->default('REQUESTED');
            $table->date('date');
            $table->string('year');
            $table->float('amount');
            $table->enum('type',['FULL_DAY','MORNING_HALF_DAY','EVENING_HALF_DAY']);
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->foreign('leave_type_id')->references('id')->on('leave_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
