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
        Schema::table('hierarchies', function (Blueprint $table) {
            $table->string('monthly_short_leave_allowance')->after('weekend_day_2')->nullable();
            $table->string('monthly_short_leave_attempts')->after('monthly_short_leave_allowance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hierarchies', function (Blueprint $table) {
            $table->dropColumn('monthly_short_leave_allowance');
            $table->dropColumn('monthly_short_leave_attempts');
        });
    }
};
