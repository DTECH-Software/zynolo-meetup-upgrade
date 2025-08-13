<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Role as UserGroup;

class AccessManagementController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Access Management');

        $data['userGroups'] = UserGroup::all();
        return view('pages.roles-and-permissions.access-management')->with($data);
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
        self::HandlePermission('Access Management');

        $userGroup = UserGroup::find($request->role);

        $userGroup->syncPermissions($request->permissions);

        return back()->with('message', 'Permissions updated successfully.');
    }

    public function getRolePermissionsView()
    {

    }

    public function getRolePermissions(Role $roleId)
    {
        self::HandlePermission('Access Management');

        $data['role'] = $roleId;

        $data['selectedPermissions'] = $roleId->permissions()->pluck('id')->toArray();

        $data['permissions'] = Permission::all();

        return view('pages.roles-and-permissions.access-management-edit')->with($data);
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
