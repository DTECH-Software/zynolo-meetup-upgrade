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
        Schema::create('transaction_assign', function (Blueprint $table) {
            $table->id();
            $table->integer('TransactionCode');
            $table->integer('EmployeeCode');
            $table->integer('TransactionMonth');
            $table->integer('TransactionYear');
            $table->float('Amount')->nullable(false)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_assign');

    }
};
