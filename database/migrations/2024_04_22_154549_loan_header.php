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
        Schema::create('loan_header', function (Blueprint $table) {
            $table->id();
            $table->string('loanname');
            $table->double('air_percentage',10,2);
            $table->integer('interest_type');
            $table->boolean('Active')->nullable(false)->default('1');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_header');

    }
};
