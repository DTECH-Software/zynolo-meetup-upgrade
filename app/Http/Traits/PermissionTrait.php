<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait PermissionTrait {

    public static function HandlePermission($permissionName)
    {
        $user = Auth::user();

         if ($user->hasPermissionTo($permissionName))
         {
            return true;

         } else {

            abort(403, 'Unauthorized action.');
         }
    }

}
