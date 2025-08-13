<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Add Details');

        return view('pages.employees.add-designations');
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
        self::HandlePermission('Add Details');

        try {
            DB::beginTransaction();
            $request->validate(['name' => 'required','abbreviation'=>'required']);
            $designation = new Designation($request->all());
            $designation->save();
            self::Log('Designation Created', 'Pass');
            DB::commit();
            return view('pages.employees.add-designations')->with('message', 'Designation Created Successfully!');

        }catch (\Exception $e) {
        DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return view('pages.employees.add-designations')->with('error','Error Adding Designations !');
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
