<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\EmploymentType;
use App\Models\JobCategory;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EmployeeDetailsController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    public $employeeData;

public function __construct()
{
    $this->employeeData = Session::get('employee_data');
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function addEmpTypes(Request $request)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validate(['name'=>'required']);
            $empType = new EmploymentType($formData);
            $empType->save();
            DB::commit();
            self::Log('Employee Type Created', 'Pass');

            return back()->with('message', 'Employee Type Created Successfully!');
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
            return  back()->with('error', 'Error Creating Employee Type!');
        }

    }

    public function addJobCategory(Request $request)
    {

        try{

         $formData = $request->validate(['name'=>'required']);

         $jobCategory = new JobCategory($formData);
         $jobCategory->save();

         return back()->with('message','JobCategory Added Successfully !');

        }catch (\Exception $e){
            self::Log($e->getMessage(), 'Fail');

            return back()->with('error','Error Creating Job Category');

        }

    }


    public function addTitles(Request $request)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validate(['name'=>'required']);
            $titleData = new Title($formData);
            $titleData->save();
            self::Log('Title Added' ,'Pass');

            DB::commit();
            return back()->with('message','Title Added Successfully !');
        }catch (\Exception $e){
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
           return back()->with('error','Error Creating the Title ! ');
        }

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
