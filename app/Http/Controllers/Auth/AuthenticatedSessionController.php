<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Employee;
use App\Models\Subscription;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $employee_id = Auth::user()->employee_id;
//dd(Auth::user());
        if ($employee_id != null){
            $employee = Employee::with('hierarchies')->find($employee_id);
            $request->session()->put('employee_data',$employee);
        }

        if ($request->session()->get('employee_data')) {
            $hierarchy_id = $request->session()->get('employee_data')->hierarchies->first()->id;
            $subscription_details = Subscription::where('hierarchy_id', $hierarchy_id)->first();

            if ($subscription_details) {
                // Check if the subscription has expired and its status
                if ($subscription_details->status !== 'ONE_TIME' && $subscription_details->status !== 'EXPIRED') {

                    // Calculate if today is within one week after the expiration date
                    $expiration_date = Carbon::parse($subscription_details->expires_at);
                    $one_week_after_expiration = $expiration_date->copy()->addWeek();

                    if (Carbon::now()->greaterThanOrEqualTo($expiration_date) && Carbon::now()->lessThanOrEqualTo($one_week_after_expiration)) {
                        // If the current date is within one week after the expiration date
                        $subscription_details->status = 'EXPIRED';
                        $subscription_details->save();

                        return redirect()->back()->withErrors([
                            'subscription' => 'Your subscription period has expired, please update your subscription.'
                        ]);
                    }

                } else if ($subscription_details->status === 'EXPIRED') {
                    // If the subscription status is already EXPIRED, show an error message
                    return redirect()->back()->withErrors([
                        'subscription' => 'Your subscription has already expired, please renew it to continue.'
                    ]);
                }
            }
        }



        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
