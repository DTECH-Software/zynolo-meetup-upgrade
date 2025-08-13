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
            $table->time('adjusted_in_time')->after('out_time')->nullable();
            $table->time('adjusted_out_time')->after('adjusted_in_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_appeals', function (Blueprint $table) {
            $table->dropColumn('adjusted_in_time');
            $table->dropColumn('adjusted_out_time');
        });
    }
};
