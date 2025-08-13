<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Bank;
use App\Models\BankBranch;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\EmploymentType;
use App\Models\Hierarchy;
use App\Models\JobCategory;
use App\Models\ResignType;
use App\Models\ShiftDetail;
use App\Models\ShiftType;
use App\Models\Subscription;
use App\Models\Title;
use App\Models\User;
use App\Models\EmployeeScheme;
use App\Models\TransactionAssign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Add Employees');

        $data = ['designations' => Designation::all(),
            'companies' => Hierarchy::all(),
            'countries' => Country::all(),
            'users' => User::all(),
            'titles' => Title::all(),
            'banks' => Bank::with('branches')->get(),
            'emptypes' => EmploymentType::all(),
            'job_categories' =>JobCategory::all(),
            'departments'=> Department::all()
        ];

        return view('pages.employees.add-employees')->with($data);
    }

    public function viewEmployees()
    {
        self::HandlePermission('View Employees');

        $employeeData = Session::get('employee_data');
//// dd($employeeData);
//        if ($employeeData != null) {
//            $data['employees'] = Employee::with('hierarchies','current_designations','res_countries','per_countries','resigntype','employeeshift')->where('company_id',$employeeData->hierarchies->id)->get();
////            dd($data['employees']);
//        }else{
            $data['employees'] = Employee::with('hierarchies','current_designations','res_countries','per_countries','resigntype','employeeshift')->get();
//        }

          $data['resign_types'] = ResignType::all();

//          $data['employees'] = Employee::all();

        return view('pages.employees.view-employees')->with($data);
    }

    public function EmployeeProfile($id)
    {
        $data['job_categories'] = JobCategory::all();
        $shift_id = EmployeeShift::where('employee_id', $id)->first();
        if($shift_id){
            $data['employee_shift']= ShiftType::find($shift_id->shift_id);
        }
        $data['designations'] = Designation::all();
        $data['resign_types'] = ResignType::all();
        $data['employement_types']= EmploymentType::all();
        $data['all_banks']= Bank::all();
        $data['banks_branches']= BankBranch::all();
        $data['all_departments'] = Department::all();
        $data['employee'] = Employee::with('hierarchies','current_designations','reporting_person','jobcategories','employmenttypes','titles','banks','branches','departments')
            ->where('id','=',$id)->first();

        return view('pages.employees.employee-profile')->with($data);
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
    public function store(EmployeeRequest $request)
    {
        $data['designations'] = Designation::all();
        $data['companies']= Hierarchy::all();
        $data['countries']= Country::all();
        $data['users']= User::all();
        $employees = Employee::where('status','ACTIVE')->get();

        $hierarchy_id = Session::get('employee_data')->hierarchies->first()->id;
        $subscription_details = Subscription::where('hierarchy_id', $hierarchy_id)->first();
        $employee_count = count($employees);

        if ($subscription_details) {
            // Check if the subscription has expired and its status
            if ($employee_count >= (int)$subscription_details->user_count) {
                return back()->with('error',"You have reached the maximum employee limit !");
            }
        }


//dd($request);
        try {
            DB::beginTransaction();

            $formData = $request->validated();
            $employee = new Employee($formData);
            $hierarchy = Hierarchy::find( $request->company_id);

            $employee->date_of_birth = Carbon::parse($employee->date_of_birth);
            $employee->date_of_retirement = $employee->date_of_birth->addYears((int)$hierarchy->age_of_retirement);

            $employee->job_category_id = $request->job_category_id;
//            dd($request->photo);
            $employee->save();

            $employee->fingerprint_id = $employee->id;
            $employee->save();


            if ($request->photo != null)
            {
                $file = $request->file('photo');
                $storagePath = 'app/uploads/employee/' . $employee->id;
                $filename = uniqid().'-'.$file->getClientOriginalName();
                $file->storeAs($storagePath, $filename, 'public');
                $filepath = $storagePath . '/' . $filename;
                $employee->photo = $filepath;
                $employee->update();
            }

            DB::commit();
            self::Log("Employee Added", 'Pass');

            return back()->with('message',"Employee Added Successfully !");

        } catch (\Exception $e) {

           DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return back()->with('error', $e->getMessage());

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
    public function update(EmployeeRequest $request, Employee $employee)
    {
        // Get the currently ACTIVE employees
        $employees = Employee::where('status', 'ACTIVE')->get();

        // Get the hierarchy ID from the session
        $hierarchy_id = Session::get('employee_data')->hierarchies->first()->id;

        // Fetch the subscription details for the current hierarchy
        $subscription_details = Subscription::where('hierarchy_id', $hierarchy_id)->first();

        // Check the current employee count for ACTIVE employees
        $employee_count = count($employees);

        // Check if the employee's status is being updated to ACTIVE
        if ($request->status == 'ACTIVE' && $employee->status != 'ACTIVE') {
            // Verify if the subscription details exist
            if ($subscription_details) {
                // Check if the employee count exceeds the subscription limit
                if ($employee_count >= (int)$subscription_details->user_count) {
                    return back()->with('error', "You have reached the maximum employee limit!");
                }
            }
        }

//        dd($request->status);
        try {
            DB::beginTransaction();

            $form_data = $request->validated();
            $employee->update($form_data);

            if ($request->photo != null)
            {
                $file = $request->file('photo');
                $storagePath = 'app/uploads/employee/' . $employee->id;
                $filename = uniqid().'-'.$file->getClientOriginalName();
                $file->storeAs($storagePath, $filename, 'public');
                $filepath = $storagePath . '/' . $filename;
                $employee->photo = $filepath;
                $employee->update();
            }


            DB::commit();
            self::Log('Employee Updated', 'Pass');

            return back()->with('message','Updated SuccessFully !');

        } catch(\Exception $e) {

            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();

            return back()->with('error','Updating Error !');
        }

    }

    public function GetEmployeesJson(Request $request)
    {

//        dd($request->shift_id);
//        dd($request);


        $shift_id = $request->shift_id;

        $user_id = Auth::user()->employee_id;
        $employee = Employee::find($user_id);

        $employees_shifts = EmployeeShift::where('shift_id',$shift_id)->pluck('employee_id');
//        $employees_shifts_all = EmployeeShift::all()->pluck('employee_id');


        $company_employees =Employee::where('company_id',$employee->company_id)->get();


        $data['shift_assgined_employees'] = Employee::where('company_id', $employee->company_id)
            ->whereIn('id', $employees_shifts)
            ->get();

        $shift_assigned_employee_ids = $data['shift_assgined_employees']->pluck('id');

        $data['unassigned_employees'] = $company_employees->filter(function ($employee) use ($shift_assigned_employee_ids) {
            return !$shift_assigned_employee_ids->contains($employee->id);
        });


//        $data['company_employees'] = $company_employees;

        return response()->json($data);


    }

    public function GetPaySchemeEmployees(Request $request){

        $payschemeId = $request->scheme_id;

        $user_id = Auth::user()->employee_id;
        $employee = Employee::find($user_id);

        $employees_scheme = EmployeeScheme::where('SchemCode',$payschemeId)->pluck('EmployeeCode');

        $company_employees =Employee::where('company_id',$employee->company_id)->get();

        $data['scheme_assgined_employees'] = Employee::where('company_id', $employee->company_id)
            ->whereIn('id', $employees_scheme)
            ->get();



        $scheme_assigned_employee_ids = $data['scheme_assgined_employees']->pluck('id');

        $data['unassigned_employees'] = $company_employees->filter(function ($employee) use ($scheme_assigned_employee_ids) {
            return !$scheme_assigned_employee_ids->contains($employee->id);
        });


        return response()->json($data);

    }

    public function GetTransactionEmployees(Request $request){

        $transactionid = $request->transaction_id;

        $user_id = Auth::user()->employee_id;
        $employee = Employee::find($user_id);

        $employee_transaction = TransactionAssign::where('TransactionCode',$transactionid)->pluck('EmployeeCode');

        $company_employees =Employee::where('company_id',$employee->company_id)->get();

        $data['transaction_assgined_employees'] = Employee::where('company_id', $employee->company_id)
            ->whereIn('id', $employee_transaction)
            ->get();

        $transaction_assigned_employee_ids = $data['transaction_assgined_employees']->pluck('id');

        $data['unassigned_employees'] = $company_employees->filter(function ($employee) use ($transaction_assigned_employee_ids) {
            return !$transaction_assigned_employee_ids->contains($employee->id);
        });

            return response()->json($data);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ListEmployee(){

        $employeesList = null;

        $employeesList = Employee::where('Status','ACTIVE')->get();

        return $employeesList;
    }

    public function GetPaySchemeAssignEmployees(){


        $user_id = Auth::user()->employee_id;
        $employee = Employee::find($user_id);

        $employees_scheme = new EmployeeScheme();

        //echo $employees_scheme;

        $company_employees =Employee::where('company_id',$employee->company_id)->get();

        $data['scheme_assgined_employees'] = Employee::where('company_id', $employee->company_id)
            ->whereIn('id', $employees_scheme)
            ->get();



        $scheme_assigned_employee_ids = $data['scheme_assgined_employees']->pluck('id');

        $data['unassigned_employees'] = $company_employees->filter(function ($employee) use ($scheme_assigned_employee_ids) {
            return !$scheme_assigned_employee_ids->contains($employee->id);
        });


        return response()->json($data);

    }
}
