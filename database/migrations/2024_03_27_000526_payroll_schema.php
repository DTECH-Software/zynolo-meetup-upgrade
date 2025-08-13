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
        Schema::create('payroll_schema', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('OTDivider')->nullable();
            $table->float('NOPAYDivider')->nullable();
            $table->float('LateDivider')->nullable();
            $table->float('EPFEmployee')->nullable();
            $table->float('EPFEmployer')->nullable();
            $table->float('ETFEmployeer')->nullable();
            $table->boolean('OTEffect')->nullable(false)->default('0');
            $table->boolean('NopayEffect')->nullable(false)->default('0');
            $table->enum('status',['ACTIVE','DEACTIVE'])->default('ACTIVE');
            $table->integer('fromattenday')->nullable();
            $table->integer('toattenday')->nullable();
            $table->timestamps();
            $table->integer('PayeeEffect');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_schema');
    }
};
