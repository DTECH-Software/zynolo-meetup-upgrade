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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->foreign('shift_id')->references('id')->on('shift_types');
            $table->date('date')->nullable();
            $table->enum('is_day_expected',['true','false'])->default('true');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->date('out_date')->nullable();
            $table->double('in_late')->nullable();
            $table->double('out_late')->nullable();
            $table->double('late_min')->nullable();
            $table->double('in_not')->nullable();
            $table->double('out_not')->nullable();
            $table->double('not')->nullable();
            $table->double('dot')->nullable();
            $table->unsignedBigInteger('leave_types_id_1')->nullable();
            $table->foreign('leave_types_id_1')->references('id')->on('leave_types')->nullable();
            $table->unsignedBigInteger('leave_types_id_2')->nullable();
            $table->foreign('leave_types_id_2')->references('id')->on('leave_types')->nullable();
            $table->double('short_leave_hrs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
