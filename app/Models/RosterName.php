<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RosterName extends Model
{
    use HasFactory;

    protected $fillable = ['roster_code', 'roster_name', 'status'];
}
