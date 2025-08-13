<?php

namespace App\Http\Traits;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AuditLogTrait {
    public static function Log($message, $status)
    {
        try {
            $audit = new AuditTrail();
            $audit->user_id = Auth::id();
            $audit->message = $message;
            $audit->status = $status;
            $audit->save();

        } catch (\Exception $e) {
            Log::error('Error Adding Audit Log', ['error' => $e->getMessage()]);


//            return response()->view('pages.error-pages.404-page', [], 500);
        }
    }
}
