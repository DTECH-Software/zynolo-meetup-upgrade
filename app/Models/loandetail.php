<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loandetail extends Model
{
    use HasFactory;

    protected $table = 'loan_details';

    protected $fillable = [
        'employeeId','loanId','loan_amount','effect_month','effect_year','hold','remain_amount','status','created_by'
    ];
}
