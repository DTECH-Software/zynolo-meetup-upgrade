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
        Schema::table('shift_details', function (Blueprint $table) {
            $table->time('half_day_in_time')->nullable()->after('midnight_crossover');
            $table->time('half_day_out_time')->nullable()->after('half_day_in_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_details', function (Blueprint $table) {
            $table->dropColumn('half_day_in_time');
            $table->dropColumn('half_day_out_time');
        });
    }
};
