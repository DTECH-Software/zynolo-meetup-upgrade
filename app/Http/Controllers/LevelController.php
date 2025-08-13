<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function addHierarchyLevelsView()
    {
        self::HandlePermission('Add Hierarchy Levels');

        $data['levels'] = Level::orderBy('level_order', 'asc')->get();
        return view('pages.levels.add-hierarchy-levels')->with($data);
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
        self::HandlePermission('Add Hierarchy Levels');

        try {

        DB::beginTransaction();

        $validatedData=$request->validate([
            'level_code'=>'required',
            'level_name'=>'required',
            'level_address'=>'required',
            'status'=>'required',
            ]);

        $max_level_order = Level::max('level_order');

        $new_level_order = $max_level_order +1;

        $level = new Level($validatedData);
        $level->level_order = $new_level_order;
        $level->save();

        DB::commit();

        $data['levels'] = Level::orderBy('level_order', 'asc')->get();
            self::Log('Level created', 'Pass');
            return view('pages.levels.add-hierarchy-levels')->with('message', 'Level created successfully !')->with($data);
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
            $data['levels'] = Level::orderBy('level_order', 'asc')->get();
            return  view('pages.levels.add-hierarchy-levels')->with('errors','Error Adding Hierarchy Levels !')->with($data);

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
