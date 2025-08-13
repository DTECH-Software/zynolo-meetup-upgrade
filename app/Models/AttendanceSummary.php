<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $table = 'attendance_summary';

    protected $fillable = [
        'employeeId','attendance_month','attendance_year','work_days','nopay_days','late_min','late_days','not','not_days','dot','dot_days','work_sundays','work_poya'
    ];
}
