<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','leave_type_id', 'year', 'allocated_leaves', 'used_leaves' ,];


    public function leavetype()
    {
        return $this->belongsTo(LeaveType::class,'leave_type_id','id');

    }
}
