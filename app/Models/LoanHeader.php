<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanHeader extends Model
{
    use HasFactory;

    protected $table = 'loan_header';

    protected $fillable = [
        'loanname','air_percentage','interest_type','Active','created_by'
    ];
}
