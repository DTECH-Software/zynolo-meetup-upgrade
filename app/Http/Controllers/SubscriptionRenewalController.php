<?php

namespace App\Http\Controllers;

use App\Mail\ActivationEmail;
use App\Models\ActivationCode;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRenewal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubscriptionRenewalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function sendActivationEmail(Request $request, $subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $hierarchy = $request->session()->get('employee_data')->hierarchies->first();

        // Generate unique activation code
        $activationCode = Str::random(16);

        // Save the activation code to the database
        ActivationCode::create([
            'subscription_id' => $subscription->id,
            'code' => $activationCode,
        ]);

        // Send activation email with link
        Mail::to($hierarchy->email)->send(new ActivationEmail($activationCode, $subscription));

        return back()->with('message', 'Activation email sent!');
    }


    public function showActivationForm(Request $request)
    {
        return view('views.subscriptions.activate', ['code' => $request->query('code'), 'subscription' => $request->query('subscription')]);
    }




//    public function activateSubscription(Request $request)
//    {
//        $request->validate([
//            'code' => 'required',
//            'email' => 'required|email',
//        ]);
//
//        // Find the activation code and subscription
//        $activation = ActivationCode::where('code', $request->input('code'))
//            ->whereHas('subscription.hierarchy', function($query) use ($request) {
//                $query->where('email', $request->input('email'));
//            })->first();
//
//        if ($activation && $activation->is_active) {
//            // Activate the subscription
//            $activation->subscription->update(['status' => 'ACTIVE']);
//
//            // Deactivate the code
//            $activation->update(['is_active' => 0]);
//
//            return redirect()->route('subscriptions.index')->with('success', 'Subscription activated successfully.');
//        }
//
//        return back()->withErrors(['Invalid code or email.']);
//    }


    public function activateSubscription($code, $subscriptionId)
    {
        // Find the activation code and subscription
        $activation = ActivationCode::where('code', $code)
            ->where('subscription_id', $subscriptionId)
            ->first();

        // Check if the activation code is valid and still active
        if ($activation && $activation->is_active) {
            // Activate the subscription
            $activation->subscription->update(['status' => 'ACTIVE']);

            // Deactivate the code
            $activation->update(['is_active' => 0]);

            $plan = Plan::where('id',$activation->subscription->plan_id)->first();
            $subscription_renewal = new SubscriptionRenewal();
            $subscription_renewal->subscription_id = $activation->subscription->id;
            $subscription_renewal->renewed_at = Carbon::now();
            $subscription_renewal->amount_paid = $plan->price;
            $subscription_renewal->save();


            // Redirect to the subscriptions index with a success message
            return redirect()->route('auth.login')->with('success', 'Subscription activated successfully. Please login.');

        }

        // If the code is invalid or the subscription is not found
        return redirect()->route('auth.login')->withErrors(['Invalid activation code or subscription. Please try again.']);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
