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
        Schema::create('hierarchies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hierarchy_level_id');
            $table->foreign('hierarchy_level_id')->references('id')->on('levels');
//            $table->unsignedBigInteger('parent_id');
//            $table->foreign('parent_id')->references('id')->on('hierarchies');
            $table->string('hierarchy_name')->nullable();
            $table->string('abbreviation')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('head_id');
            $table->foreign('head_id')->references('id')->on('users');
            $table->string('title_of_head')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('users');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('logo_path')->nullable();
            $table->string('header_path')->nullable();
            $table->string('footer_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hierarchies');
    }
};
