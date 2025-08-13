<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = ['leave_details_id','date','year','type','leave_type_id','amount','employee_id'];

    public function leavedetails() {
        return $this->belongsTo(LeaveDetail::class, 'leave_details_id','id');
    }

    public function leavetype(){
        return $this->belongsTo(LeaveDetail::class,'leave_type_id','id');
    }



}
