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
            $table->enum('weekend_day_1',['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'])->after('footer_path')->nullable();
            $table->enum('weekend_day_2',['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'])->after('weekend_day_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::table('hierarchies', function (Blueprint $table) {
            $table->dropColumn('weekend_day_1');
            $table->dropColumn('weekend_day_2');
        });
    }
};
