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
        Schema::create('shift_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_type_id');
            $table->foreign('shift_type_id')->references('id')->on('shift_types');
            $table->unsignedBigInteger('day_type_id');
            $table->foreign('day_type_id')->references('id')->on('holiday_types');
            $table->enum('day_expected',['true','false'])->default('false');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->double('late_grace_min')->nullable();
            $table->double('late_after')->nullable();
            $table->double('ot_after')->nullable();
            $table->enum('is_in_ot_calculate',['true','false'])->default('false');
            $table->enum('is_out_ot_calculate',['true','false'])->default('false');
            $table->double('dot_after')->nullable();
            $table->double('tot_after')->nullable();
//            $table->double('late_round');
//            $table->double('ot_round');
            $table->double('half_day_length')->nullable();
            $table->double('flexi_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_details');
    }
};
