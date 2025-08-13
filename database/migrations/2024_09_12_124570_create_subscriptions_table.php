<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hierarchy_id');
            $table->foreign('hierarchy_id')->references('id')->on('hierarchies');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at')->nullable();
            $table->enum('status',['ACTIVE','EXPIRED','ONE_TIME'])->default('ACTIVE');
            $table->integer('user_count');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
