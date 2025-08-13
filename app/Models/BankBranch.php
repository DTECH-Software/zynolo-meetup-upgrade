<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'name',
        'address',
        'contact_no',
        'branch_code'
    ];

    public function banks()
    {
        return $this->belongsTo(Bank::class,'bank_id','id');

    }
}
