<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['day_type','country_id','company_id', 'year' , 'date', 'day' , 'name' ,'status', 'holiday_type_id',];
}
