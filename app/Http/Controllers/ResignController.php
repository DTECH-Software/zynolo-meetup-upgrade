<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\ResignType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResignController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeResignTypes(Request $request)
    {

        try {
              DB::beginTransaction();
            $formData = $request->validate(['name' => 'required']);

            $resignType = new ResignType($formData);

            $resignType->save();

            DB::commit();
            self::Log('Resign Type Added', 'Pass');

            return back()->with('message','Resign Type Added Successfully !');

        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollback();

            return  back()->with('error','Error Adding Resign Types !');

        }


    }




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
