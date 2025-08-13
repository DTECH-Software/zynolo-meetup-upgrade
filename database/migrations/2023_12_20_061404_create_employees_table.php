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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fingerprint_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('name_with_initials');
            $table->string('photo')->nullable();
            $table->string('known_name')->nullable();
//            $table->string('title');
            $table->unsignedBigInteger('title_id')->nullable();
            $table->foreign('title_id')->references('id')->on('titles')->onUpdate('cascade')->onDelete('set null');
            $table->string('gender');
//            $table->enum('relationship',['married','unmarried'])->nullable();

            $table->enum('marital_status',['married','unmarried'])->nullable();
            $table->unsignedBigInteger('resign_type_id')->nullable();
            $table->foreign('resign_type_id')->references('id')->on('resign_types')->onDelete('set null');
            $table->unsignedBigInteger('employment_type_id')->nullable();
            $table->foreign('employment_type_id')->references('id')->on('employment_types')->onDelete('set null');
            $table->date('date_of_birth');
            $table->string('nic');
            $table->string('passport')->nullable();
            $table->string('religion');
            $table->string('nationality');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
            $table->unsignedBigInteger('bank_branch_id')->nullable();
            $table->foreign('bank_branch_id')->references('id')->on('bank_branches')->onDelete('set null')->onUpdate('cascade');
            // ...

            // Employment details
            $table->string('employee_number');
            $table->string('epf_etf_number');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('hierarchies');
            $table->enum('status',['ACTIVE', 'DEACTIVE'])->default('ACTIVE');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedBigInteger('current_designation');//need a new table to be created to store designations - designation name and id is enough - also need a new table to store designations history
            $table->foreign('current_designation')->references('id')->on('designations');
            $table->unsignedBigInteger('joined_designation')->nullable();
            $table->foreign('joined_designation')->references('id')->on('designations');
            $table->unsignedBigInteger('job_category_id')->nullable();
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('reporting_person_id')->nullable();
            $table->foreign('reporting_person_id')->references('id')->on('users');
            $table->date('date_of_appointment');
//            $table->string('employment_type')->nullable();
            $table->date('confirmation_due')->nullable();
            $table->date('confirmed_on')->nullable();
            $table->date('date_of_retirement')->nullable();
            $table->date('date_of_resign')->nullable();
            $table->date('effective_date_of_resign')->nullable();
            // ...

            // Other details
            $table->string('scheme');
            $table->string('reason_for_resigned')->nullable();
            $table->boolean('eligibility_for_ot')->nullable();
//            $table->string('resign_type')->nullable();
            $table->float('basic_salary', 20)->nullable();
            // ...

            // Residential address
            $table->string('res_apartment_building_no')->nullable();
            $table->string('res_street');
            $table->string('res_city');
            $table->string('res_district');
            $table->string('res_province');
            $table->string('res_electorate')->nullable();
            $table->unsignedBigInteger('res_country');
            $table->foreign('res_country')->references('id')->on('countries');

            // ...

            // Permanent address
            $table->string('per_apartment_building_no')->nullable();
            $table->string('per_street');
            $table->string('per_city');
            $table->string('per_district');
            $table->string('per_province');
            $table->string('per_electorate')->nullable();
            $table->unsignedBigInteger('per_country');
            $table->foreign('per_country')->references('id')->on('countries');
            // ...

            // Contact details
            $table->string('contact_number');
            $table->string('email_address');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
