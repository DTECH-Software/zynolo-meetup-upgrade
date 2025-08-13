<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAssign extends Model
{
    use HasFactory;

    protected $table = 'transaction_assign';

    protected $fillable = [
        'TransactionCode','EmployeeCode','TransactionMonth','TransactionYear','Amount'
    ];
}
