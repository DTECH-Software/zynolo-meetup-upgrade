<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    Protected $fillable = [
    'employee_id',
    'shift_id',
    'date',
    'is_day_expected',
    'in_time',
    'out_time',
    'out_date',
    'adjusted_in_time',
    'adjusted_out_time',
    'in_late',
    'out_late',
    'late_min',
    'in_not',
    'out_not',
    'not',
    'dot',
    'leave_types_id_1',
    'leave_types_id_2',
    'short_leave_hrs',
    'status',
    'status',
    'clock_in_time',
    'clock_out_time',
    'late_appeal_status'
    ];


    public  function employees()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }


}
