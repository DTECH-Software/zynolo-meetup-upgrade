<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    Protected $fillable = [ 'name','abbreviation'];

    public function branches()
    {
        return $this->hasMany(BankBranch::class,'bank_id','id');
    }
}
