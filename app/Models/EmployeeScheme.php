<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeScheme extends Model
{
    use HasFactory;
    protected $table = 'payroll_schema_assign';
    protected $fillable = [ 'EmployeeCode' , 'SchemCode'];
}
