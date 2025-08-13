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
        Schema::table('short_leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('short_leave_type_id')->nullable()->after('review');
            $table->foreign('short_leave_type_id')->references('id')->on('short_leave_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('short_leaves', function (Blueprint $table) {
            $table->dropForeign('short_leaves_short_leave_type_id_foreign');
            $table->dropColumn('short_leave_type_id');
        });
    }
};
