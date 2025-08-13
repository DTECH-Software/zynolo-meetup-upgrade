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
          $table->time('in_time')->change();
          $table->time('out_time')->change();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_appeals', function (Blueprint $table) {

        });
    }
};
