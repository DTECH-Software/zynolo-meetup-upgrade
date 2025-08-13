<?php

namespace App\Models;

use App\Http\Controllers\EmployeeDetailsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDetail extends Model
{

    use HasFactory;

    protected $fillable = [
        'shift_type_id',
        'day_type_id',
        'day_expected',
        'in_time',
        'out_time',
        'late_grace_min',
        'late_after',
        'ot_after',
        'is_in_ot_calculate',
        'is_out_ot_calculate',
        'dot_after',
        'tot_after',
//        'late_round',
//        'ot_round',
        'half_day_length',
        'flexi_hours',
        'midnight_crossover',
        'half_day_in_time',
        'half_day_out_time',
    ];

}
