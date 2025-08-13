<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HierarchyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
//       if( Auth::user()->authorized){
           return true;
//       }else{
//           return false;
//       }

    }


    public function rules(): array
    {
        return [
            'hierarchy_level_id'=>'required',
            'hierarchy_name'=>'required',
            'abbreviation'=>'required',
            'telephone'=>'required',
            'fax'=>'nullable',
            'email'=>'required',
            'head_id'=>'required',
            'title_of_head'=>'required,',
            'admin_id'=>'required',
            'country_id'=>'required',
            'weekend_day_1'=>'nullable',
            'weekend_day_2'=>'nullable',
            'monthly_short_leave_allowance'=>'nullable',
            'monthly_short_leave_attempts'=>'nullable',

        ];
    }
}
