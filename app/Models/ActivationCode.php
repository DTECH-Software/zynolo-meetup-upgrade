<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id','code','is_active'];


    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
