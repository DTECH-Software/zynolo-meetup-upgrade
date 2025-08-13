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
        Schema::create('roster_names', function (Blueprint $table) {
            $table->id();
            $table->string('roster_code')->nullable();
            $table->string('roster_name')->nullable();
            $table->enum('status',['ACTIVE','DEACTIVE'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roster_names');
    }
};
