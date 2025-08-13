<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryEntry extends Model
{
    use HasFactory;

    protected $table = 'salary_entry';

    protected $fillable = [
        'EntryName','EntryType','VariableType','PAYEEffect','EPFEffect','LateEffect','NopayEffect','Active'
    ];
}
