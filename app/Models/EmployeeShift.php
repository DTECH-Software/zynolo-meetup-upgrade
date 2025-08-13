<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory;
    protected $fillable = [ 'employee_id' , 'shift_id'];


    public function shift()
    {
        $this->belongsTo(ShiftDetail::class, 'shift_id','id');
    }

    public function employee()
    {
        $this->belongsTo(Employee::class, 'employee_id','id');
    }

}


