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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_appointment')->nullable()->change();
            $table->string('scheme')->nullable()->change();
            $table->unsignedBigInteger('res_country')->nullable()->change();
            $table->unsignedBigInteger('per_country')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_appointment')->change();
            $table->string('scheme')->change();
            $table->unsignedBigInteger('res_country')->change();
            $table->unsignedBigInteger('per_country')->change();
        });
    }
};
