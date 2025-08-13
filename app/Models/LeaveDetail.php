<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveDetail extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','leave_type_id','holiday_type','half_day_type','short_leave_type','leave_type_id','leave_date','leave_amount','approver_id','status','leave_from','leave_to'];

    public function leaves()
    {
        return $this->hasMany(Leave::class,'leave_details_id','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

    public function leavetype()
    {
        return $this->belongsTo(LeaveType::class,'leave_type_id','id');
    }
}
