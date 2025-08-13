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
        Schema::create('attendance_summary', function (Blueprint $table) {
            $table->id();
            $table->integer('employeeId');
            $table->integer('attendance_month');
            $table->integer('attendance_year');
            $table->double('work_days',10,2);
            $table->double('nopay_days',10,2);
            $table->double('late_min',10,2);
            $table->double('late_days',10,2);
            $table->double('not',10,2);
            $table->double('not_days',10,2);
            $table->double('dot',10,2);
            $table->double('dot_days',10,2);
            $table->double('work_sundays',10,2);
            $table->double('work_poya',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_summary');

    }
};
