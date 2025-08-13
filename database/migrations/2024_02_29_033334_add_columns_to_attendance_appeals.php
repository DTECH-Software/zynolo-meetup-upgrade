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
        Schema::table('attendance_appeals', function (Blueprint $table) {
            $table->date('date')->after('status');
            $table->enum('appeal_type',['in','out','both'])->default('both')->after('date');
            $table->unsignedBigInteger('attendance_id')->after('appeal_type');
            $table->foreign('attendance_id')->references('id')->on('attendances');
            $table->unsignedBigInteger('employee_id')->after('attendance_id');
            $table->foreign('employee_id')->references('id')->on('employees');
//            $table->dropForeign('attendance_appeals_leave_id_foreign');
//            $table->dropColumn('leave_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_appeals', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('appeal_type');
            $table->dropForeign('attendance_appeals_attendance_id_foreign');
            $table->dropColumn('attendance_id');
            $table->dropForeign('attendance_appeals_employee_id_foreign');
            $table->dropColumn('employee_id');
        });
    }
};
