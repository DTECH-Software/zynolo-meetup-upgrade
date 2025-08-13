<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = ['name','is_unlimited','is_paid','amount'];

    public function leavedetail()
    {
        return $this->hasMany(LeaveDetail::class,'leave_type_id','id');
    }

}
