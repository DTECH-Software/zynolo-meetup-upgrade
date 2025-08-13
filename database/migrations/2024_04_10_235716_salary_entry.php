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
        Schema::create('salary_entry', function (Blueprint $table) {
            $table->id();
            $table->string('EntryName');
            $table->integer('EntryType');
            $table->integer('VariableType');
            $table->boolean('PAYEEffect')->nullable(false)->default('0');
            $table->boolean('EPFEffect')->nullable(false)->default('0');
            $table->boolean('LateEffect')->nullable(false)->default('0');
            $table->boolean('NopayEffect')->nullable(false)->default('0');
            $table->boolean('Active')->nullable(false)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_entry');

    }
};
