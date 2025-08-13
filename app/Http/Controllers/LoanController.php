<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\LoanHeader;
use App\Models\loandetail;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.payroll.loan');
    }

    public function assignloans()
    {
        return view('pages.payroll.assignloan');
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
       try{

        DB::beginTransaction();

        $employee_id = \Illuminate\Support\Facades\Auth::user()->employee_id;

        $modelLoan = new LoanHeader();

        $modelLoan->loanname = $request->input('LoanName');
        $modelLoan->air_percentage = $request->input('ipercent');
        $modelLoan->interest_type = $request->input('IntersetType');
        $modelLoan->Active = $request->input('Status');
        $modelLoan->created_by = $employee_id;;
        $modelLoan->save();

           self::Log('Loan Stored', 'Pass');

        DB::commit();


       }catch (\Exception $e) {

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

    public function GetLoanDetailsbyId(Request $request){


        $loanListById = LoanHeader::where('id', $request->loanid)->get();

        return $loanListById;

    }

    public function ListLoanDetails(){

        $loanList = null;

        //$loanList = DB::table('loan_header')->get();
        $loanList = LoanHeader::where('Active',1)->get();

        echo json_encode($loanList);
    }

    public function ListLoanDetailsWithoutJson(){

        $loanList = null;

        //$loanList = DB::table('loan_header')->get();
        $loanList = LoanHeader::where('Active',1)->get();

        return $loanList;
    }

    public function UpdateLoan(Request $request){

        $modelLoan = LoanHeader::where('id', $request->input('loanId'))
                    ->update(['loanname'=>$request->input('LoanName'),
                             'air_percentage'=>$request->input('ipercent'),
                             'interest_type'=>$request->input('IntersetType'),
                             'Active'=>$request->input('Status')
                ]);

    }

    public function AssignLoan(Request $request){

        try{

            DB::beginTransaction();

            $employee_id = \Illuminate\Support\Facades\Auth::user()->employee_id;

            $modelLoanAssign = new loandetail();

            $modelLoanAssign->employeeId = $request->input('employeeid');
            $modelLoanAssign->loanId = $request->input('loanid');
            $modelLoanAssign->loan_amount = $request->input('loanamount');
            $modelLoanAssign->effect_month = $request->input('effectmonth');
            $modelLoanAssign->effect_year = $request->input('effectyear');
            $modelLoanAssign->hold = $request->input('hstatus');
            $modelLoanAssign->remain_amount = $request->input('loanamount');
            $modelLoanAssign->status = 0;
            $modelLoanAssign->created_by = $employee_id;
            $modelLoanAssign->save();

            self::Log('Loan Assigned', 'Pass');

            DB::commit();


        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();

            return back()->with('error', $e->getMessage());

        }
    }

    public function GetLoanAssignList(){

        $loanAssignList = null;

        $loanAssignList = DB::table('loan_details')
                          ->join('employees','employees.id','=','loan_details.employeeId')
                          ->join('loan_header','loan_header.id','=','loan_details.loanId')
                          ->select('employees.first_name','employees.last_name','employees.employee_number','loan_header.loanname','loan_header.air_percentage','loan_header.interest_type','loan_details.remain_amount','loan_details.loan_amount')
                          ->get();

       echo json_encode($loanAssignList);
    }
}
