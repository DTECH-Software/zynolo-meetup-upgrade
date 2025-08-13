<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shift_type_id'=>'required',
            'day_type_id'=>'required',
    'day_expected'=>'nullable',
    'in_time'=>'nullable',
    'out_time'=>'nullable',
    'late_grace_min'=>'nullable',
    'late_after'=>'nullable',
    'ot_after'=>'nullable',
    'is_in_ot_calculate'=>'nullable',
    'is_out_ot_calculate' => 'nullable',
    'dot_after' => 'nullable',
    'tot_after' => 'nullable',
//    'late_round' => 'nullable',
//    'ot_round' => 'nullable',
    'half_day_length' => 'nullable',
    'flexi_hours' => 'nullable',
    'midnight_crossover' => 'nullable',
    'half_day_in_time' => 'nullable',
    'half_day_out_time' => 'nullable',
        ];
    }
}
