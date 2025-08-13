<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [  
        'first_name',
        'fingerprint_id',
        'last_name' ,
        'full_name' ,
        'name_with_initials' ,
        'photo',
        'known_name' ,
        'title' ,
        'gender' ,
        'marital_status' ,
        'date_of_birth' ,
        'passport',
        'religion' ,
        'nationality' ,
        'employee_number' ,
        'epf_etf_number' ,
        'company_id' ,
        'departments' ,
        'current_designation' ,
        'joined_designation' ,
        'job_category' ,
        'reporting_person_id' ,
        'date_of_appointment',
        'confirmation_due',
        'confirmed_on',
        'date_of_retirement',
        'date_of_resign',
        'scheme',
        'reason_for_resigned',
        'eligibility_for_ot',
//        'resign_type',
        'basic_salary' ,
        'res_apartment_building_no' ,
        'res_city' ,
        'res_street' ,
        'res_district' ,
        'res_province' ,
        'res_electorate' ,
        'res_country' ,
        'per_apartment_building_no' ,
        'per_street' ,
        'per_city' ,
        'per_district' ,
        'per_province' ,
        'per_electorate' ,
        'per_country' ,
        'contact_number' ,
        'email_address' ,
        'emergency_contact_name' ,
        'emergency_contact_number' ,
        'relationship' ,
        'effective_date_of_resign' ,
        'nic',
        'bank_id',
        'bank_branch_id',
        'job_category_id',
        'employment_type_id',
        'title_id',
        'resign_type_id',
        'department_id',
        'status'


    ];


    public function hierarchies()
    {
        return $this->belongsTo(Hierarchy::class,'company_id','id');
    }

//current_designation
     public function current_designations()
     {
        return $this->belongsTo(Designation::class,'current_designation','id');
     }

     public function reporting_person()
     {
        return $this->belongsTo(User::class,'reporting_person_id','id');
     }

    public function res_countries()
    {
         return $this->belongsTo(Country::class,'res_country','id');
     }

     public function per_countries()
     {
         return $this->belongsTo(Country::class,'per_country','id');
     }

    public function resigntype()
    {
        return $this->belongsTo(Employee::class,'resign_type_id','id');

     }

    public function titles()
    {

        return $this->belongsTo(Title::class,'title_id','id');

     }

    public function employmenttypes()
    {
        return $this->belongsTo(EmploymentType::class,'employment_type_id','id');

     }

    public function banks()
    {
        return $this->belongsTo(Bank::class,'bank_id','id');

    }

    public function branches()
    {
        return $this->belongsTo(BankBranch::class,'bank_branch_id','id');

     }

    public function jobcategories()
    {
        return $this->belongsTo(JobCategory::class,'job_category_id','id');
     }

    public function departments()
    {
        return $this->belongsTo(Department::class,'department_id','id');
     }


    public function employeeshift()
    {
        return $this->hasMany(EmployeeShift::class,'employee_id','id');
    }

    public function leavedetails()
    {
     return $this->hasMany(LeaveDetail::class,'employee_id','id');
    }

//    public  function company()
//    {
//        return $this->hasMany(Hier::class,'')
//    }



}
