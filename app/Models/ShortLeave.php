<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLeave extends Model
{
    use HasFactory;
protected $table = 'short_leaves';
    protected $fillable = [
        'employee_id','date','short_leave_type','status','hours_amount','approver_id','approver_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

}
