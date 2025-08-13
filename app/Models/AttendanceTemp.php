<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'fingerprint_id',
        'shift_id',
        'date',
        'time',
        ];
}
