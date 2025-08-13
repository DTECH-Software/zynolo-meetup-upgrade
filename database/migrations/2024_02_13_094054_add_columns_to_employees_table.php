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
            $table->string('epf_etf_number')->after('employee_number')->nullable()->change();
            $table->string('res_apartment_building_no')->nullable()->change();
            $table->string('per_apartment_building_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('epf_etf_number')->after('employee_number')->change();
            $table->string('res_apartment_building_no')->change();
            $table->string('per_apartment_building_no')->change();
        });
    }
};
