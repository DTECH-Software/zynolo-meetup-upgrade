<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRenewal extends Model
{
    use HasFactory;

    protected $table = 'subscription_renewals';

    protected $fillable = [
        'subscription_id',
        'renewed_at',
        'amount_paid',
        'payment_method',
        'transaction_id',
    ];

    // Define a relationship with the Subscription model
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
