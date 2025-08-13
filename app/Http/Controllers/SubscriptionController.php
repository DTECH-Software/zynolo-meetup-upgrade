<?php

namespace App\Http\Controllers;

use App\Jobs\SendExpirationMail;
use App\Models\Hierarchy;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch companies and plans to display in the dropdown
        $data['companies'] = Hierarchy::all();
        $data['plans'] = Plan::all();

        $data['subscriptions'] = Subscription::with('hierarchy', 'plan')->get();

        return view('pages.subscriptions.subscriptions')->with($data);
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
        try {
            DB::beginTransaction();

            // Validate the incoming request
            $validated = $request->validate([
                'company_id' => 'required',
                'plan_id' => 'required',
            ]);

            // Fetch plan details
            $plan_details = Plan::find($request->plan_id);

            // Create a new subscription
            $subscription = new Subscription();
            $subscription->hierarchy_id = $request->company_id;
            $subscription->plan_id = $request->plan_id;
            $subscription->starts_at = Carbon::now();
            // Correct way to add days
            $subscription->expires_at = Carbon::now()->addDays($plan_details->duration_in_days);
            $subscription->status = 'ACTIVE';
            $subscription->user_count = $plan_details->default_user_count;
            $subscription->save();

            // Fetch hierarchy details
            $hierarchy = $subscription->hierarchy;

//             Dispatch the job to send the expiration email notification
            SendExpirationMail::dispatch($hierarchy, $subscription)
                ->delay($subscription->expires_at->subWeek());

//            SendExpirationMail::dispatch($hierarchy, $subscription)
//                ->delay(now()->addMinute(1));


            DB::commit();
            return back()->with('message', 'Plan assigned to company successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
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
