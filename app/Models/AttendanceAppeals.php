<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceAppeals extends Model
{
    use HasFactory;

    protected $fillable = ['in_time','date','appeal_type','attendance_id','out_time','adjusted_in_time','adjusted_out_time', 'reason','status','employee_id','no_of_hours'];


    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

}
