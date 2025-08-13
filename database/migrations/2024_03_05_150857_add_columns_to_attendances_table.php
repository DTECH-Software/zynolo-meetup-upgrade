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
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status',['VALID','INVALID','EARLY_DEPARTURE','LATE'])->after('short_leave_hrs')->default('VALID')->change();
            $table->string('clock_in_time')->nullable()->after('status');
            $table->string('clock_out_time')->nullable()->after('clock_in_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status',['VALID','INVALID'])->after('short_leave_hrs')->default('VALID')->change();
            $table->dropColumn('clock_in_time');
            $table->dropColumn('clock_out_time');
        });
    }
};
