<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_in_days',
        'default_user_count',
    ];

    // Define a relationship with the Subscription model
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
