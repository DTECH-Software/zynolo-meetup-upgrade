<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFieldsNullableInEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Make first_name, last_name, and email_address nullable
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('email_address')->nullable()->change();
            $table->string('date_of_birth')->nullable()->change();
            $table->string('employee_number')->nullable()->change();
            $table->string('per_street')->nullable()->change();
            $table->string('contact_number')->nullable()->change();
            $table->string('nic')->nullable()->change();
            $table->string('res_street')->nullable()->change();
            $table->string('res_district')->nullable()->change();
            $table->string('res_province')->nullable()->change();
            $table->string('res_city')->nullable()->change();
//            $table->string('per_street')->nullable()->change();
            $table->string('per_city')->nullable()->change();
            $table->string('per_district')->nullable()->change();
            $table->string('per_province')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Revert the changes
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('email_address')->nullable(false)->change();
            $table->string('date_of_birth')->nullable(false)->change();
            $table->string('employee_number')->nullable(false)->change();
            $table->string('per_street')->nullable(false)->change();
            $table->string('contact_number')->nullable(false)->change();
            $table->string('nic')->nullable(false)->change();
            $table->string('res_street')->nullable(false)->change();
            $table->string('res_district')->nullable(false)->change();
            $table->string('res_province')->nullable(false)->change();
            $table->string('per_district')->nullable(false)->change();
//            $table->string('res_province')->nullable(false)->change();
//            $table->string('per_street')->nullable(false)->change();
            $table->string('per_city')->nullable(false)->change();
//            $table->string('per_district')->nullable(false)->change();
            $table->string('per_province')->nullable(false)->change();


        });
    }
}
