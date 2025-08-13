<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'hierarchy_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'status',
        'user_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Define relationships
    public function hierarchy()
    {
        return $this->belongsTo(Hierarchy::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function renewals()
    {
        return $this->hasMany(SubscriptionRenewal::class);
    }
}
