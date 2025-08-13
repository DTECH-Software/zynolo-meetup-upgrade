<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionExpiringSoon;
use App\Models\Hierarchy;
use App\Models\Subscription;

class SendExpirationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hierarchy;
    public $subscription;

    /**
     * Create a new job instance.
     *
     * @param Hierarchy $hierarchy
     * @param Subscription $subscription
     */
    public function __construct(Hierarchy $hierarchy, Subscription $subscription)
    {
        $this->hierarchy = $hierarchy;
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send the email notification
        Mail::to($this->hierarchy->email)->send(new SubscriptionExpiringSoon($this->hierarchy, $this->subscription));
    }
}
