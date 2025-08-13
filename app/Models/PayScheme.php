<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayScheme extends Model
{
    use HasFactory;

    protected $table = 'payroll_schema';

    protected $fillable = [
        'name','OTDivider','NOPAYDivider','LateDivider','EPFEmployee','EPFEmployer','ETFEmployeer','OTEffect','NopayEffect','status','fromattenday','toattenday','PayeeEffect'
    ];
}
