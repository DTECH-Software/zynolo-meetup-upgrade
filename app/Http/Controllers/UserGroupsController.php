<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as UserGroup;

class UserGroupsController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Manage User Groups');  // from permission trait

        $data['userGroups'] = UserGroup::all();

        return view('pages.roles-and-permissions.add-roles')->with($data);
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
        self::HandlePermission('Manage User Groups');

        UserGroup::create([
            'name' => $request->user_group,
            'guard_name' => 'web'
        ]);

        return back()->with('message', 'User group created successfully.');
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
        self::HandlePermission('Manage User Groups');

        try {

            DB::beginTransaction();

            UserGroup::destroy($id);

            DB::commit();
            self::Log('User group deleted', 'Pass');

            return back()->with('message', 'User group deleted successfully.');

        } catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();

//            return back()->with('error', $e->getMessage());
            return back()->with('error', 'Failed to delete the user group.');
        }
    }
}
