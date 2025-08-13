<?php

namespace App\Models;

use App\Http\Controllers\EmployeeDetailsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    use HasFactory;

    protected $fillable = ['shift_code', 'shift_name', 'status'];


    public function employeeshift()
    {
        return $this->belongsTo(EmployeeShift::class,'shift_id','id');
    }



}
