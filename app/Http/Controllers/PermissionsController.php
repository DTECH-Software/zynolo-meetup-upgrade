<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Access Management');

        $data['permissions'] = Permission::all();

        return view('pages.roles-and-permissions.add-permissions')->with($data);
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

        Permission::create([
            'name' => $request->permission,
            'guard_name' => 'web',
            'level' => $request->level,
        ]);

        return back()->with('message', 'Permission created successfully.');
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
        self::HandlePermission('Access Management');

        try {

            DB::beginTransaction();

            Permission::destroy($id);

            DB::commit();
            self::Log('Permission deleted', 'Pass');

            return back()->with('message', 'Permission deleted successfully.');

        } catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();

//            return back()->with('error', $e->getMessage());
            return back()->with('error', 'Failed to delete the permission.');
        }
    }
}
