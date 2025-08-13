<?php

namespace App\Http\Controllers;

use App\Http\Requests\HierarchyRequest;
use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Country;
use App\Models\Department;
use App\Models\Hierarchy;
use App\Models\HierarchyParentChild;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HierarchyController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */

    public $employeeData;

    public function __construct()
    {
        $this->employeeData = Session::get('employee_data');
    }
    public function index()
    {
        self::HandlePermission('Add Hierarchies');

        $data['countries'] = Country::all();
        $data['hierarchies'] = Hierarchy::all();
        $data['levels'] = Level::all();
        $data['users'] = User::all();
        return view('pages.levels.add-hierarchy')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function returnHierarchies(Request $request)
    {
        self::HandlePermission('Add Hierarchy Levels');

        $id=$request->input('id');
        $filterdata = Hierarchy::where('hierarchy_level_id', '=', $id)->get();
        return response()->json($filterdata);
    }



    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HierarchyRequest $request)
    {

        try {
            DB::beginTransaction();

            $data = [
                'countries' => Country::all(),
                'hierarchies' => Hierarchy::all(),
                'levels' => Level::all(),
                'users' => User::all(),
            ];

            $form_data = $request->validated();
            $hierarchy = new Hierarchy($form_data);

//            'logo' => 'nullable|max:2048',
//            'header'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'footer'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'parent_id'=>'nullable',



            $hierarchy->save();

            if ($request->file('logo')!= null){
                $logofile = $request->file('logo');
                $storagePath = 'uploads/logo/' . $hierarchy->id;
                $filename = uniqid() . '_' . $logofile->getClientOriginalName();
                $logofile->storeAs($storagePath, $filename, 'public');
                $filepath = $storagePath . '/' . $filename;
                $hierarchy->logo_path = $filepath;
                $hierarchy->update();

            }


            if ($request->file('header') != null ){
                $headerfile = $request->file('header');

                $storagePath = 'uploads/logo/' . $hierarchy->id;
                $filename = uniqid() . '_' . $headerfile->getClientOriginalName();
                $headerfile->storeAs($storagePath, $filename, 'public');
                $filepath = $storagePath . '/' . $filename;
                $hierarchy->header_path = $filepath;
                $hierarchy->update();

            }

            if ($request->file('footer') != null ) {
                $footerfile = $request->file('footer');
                $storagePath = 'uploads/logo/' . $hierarchy->id;
                $filename = uniqid() . '_' . $footerfile->getClientOriginalName();
                $footerfile->storeAs($storagePath, $filename, 'public');
                $filepath = $storagePath . '/' . $filename;
                $hierarchy->logo_path = $filepath;
                $hierarchy->update();

            }




            $Hierarchy_Parent_Child=new HierarchyParentChild();
//          dd($request->parent_id);
            if ($request->parent_id != null ){
                $Hierarchy_Parent_Child->child_id = $hierarchy->id;
                $Hierarchy_Parent_Child->parent_id = $request->parent_id;
                $Hierarchy_Parent_Child->save();
               }


            DB::commit();
            self::Log('New Hierarchy Added', 'Pass');

            return view('pages.levels.add-hierarchy')->with('message', 'New Hierarchy Added Successfully!')->with($data);
        } catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();

            return view('pages.levels.add-hierarchy')->with('error', $e->getMessage())->with($data);
        }
    }

    public  function ViewHierarchy()
    {
        self::HandlePermission('View Hierarchies');

        $data['levels']= Level::all();
        $data['hierarchy']=Hierarchy::with('parents','children')->get();
        return view('pages.levels.view-hierarchies')->with($data);
    }


    public function DeprtmentsAddView()
    {
        self::HandlePermission('Add Departments');

        $data['hierarchies']=Hierarchy::all();
        return view('pages.levels.add-departments')->with($data);

    }

    public function addDepartments(Request $request)
    {
        self::HandlePermission('Add Departments');

        try {
           DB::beginTransaction();
            $formData = $request->validate(['name'=>'required','abbreviation'=>'required','hierarchy_id'=>'required']);

            $department = new Department($formData);

            $department->save();
            DB::commit();
            return back()->with('message','Department Added Successfully !');
        }catch (\Exception $e) {
           DB::rollback();
           return back()->with('error','Error Adding Department !');
        }

    }

    public function getDepartmentsById(Request $request)
    {
        self::HandlePermission('View Hierarchies');

        $departments = Department::where('hierarchy_id','=',$request->id)->get();

        return response()->json($departments);

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
