<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'full_name' => 'required',
            'name_with_initials' => 'required',
            'known_name' => 'nullable',
            'title_id' => 'nullable',
            'gender' => 'required',
            'marital_status' => 'required',
            'date_of_birth' => 'required',
            'fingerprint_id' => 'nullable',
            'passport' => 'nullable',
            'religion' => 'required',
            'nationality' => 'required',
            'employee_number' => 'required',
            'epf_etf_number' => 'nullable',
            'company_id' => 'required',
            'departments' => 'nullable',
            'current_designation' => 'required',
//            'joined_designation' => 'required',
//            'job_category' => 'required',
            'reporting_person_id' => 'nullable',
            'date_of_appointment' => 'nullable',
            'confirmation_due' => 'nullable',
            'confirmed_on' => 'nullable',
            'date_of_retirement' => 'nullable',
            'date_of_resign' => 'nullable',
            'scheme' => 'nullable',
            'reason_for_resigned' => 'nullable',
            'eligibility_for_ot' => 'nullable',
//            'resign_type' => 'nullable',
            'basic_salary' => 'nullable',
            'res_apartment_building_no' => 'nullable',
            'res_city' => 'nullable',
            'res_street'=>'nullable',
            'res_district' => 'nullable',
            'res_province' => 'nullable',
            'res_electorate' => 'nullable',
            'res_country' => 'nullable',
            'per_apartment_building_no' => 'nullable',
            'per_street' => 'nullable',
            'per_city' => 'nullable',
            'per_district' => 'nullable',
            'per_province' => 'nullable',
            'bank_id'=>'nullable',
            'bank_branch_id'=>'nullable',
            'per_electorate' => 'nullable',
            'per_country' => 'nullable',
            'contact_number' => 'nullable',
            'email_address' => 'nullable',
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required',
            'nic' => 'required',
            'photo' => 'nullable',
            'effective_date_of_resign' => 'nullable',
            'job_category_id'=>'required',
            'employment_type_id'=>'required',
            'resign_type_id'=>'nullable',
            'department_id'=>'nullable',
            'status'=>'nullable',

        ];
    }
}
