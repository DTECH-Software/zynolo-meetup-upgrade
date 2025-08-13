<?php

namespace App\Http\Controllers;

use App\Models\payroll;
use App\Models\PayScheme;
use App\Models\EmployeeScheme;
use App\Models\AttendanceSummary;
use App\Models\Employee;
use App\Models\TransactionAssign;
use App\Models\SalaryEntry;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.payroll.payprocess');
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

    public function payroll_process(Request $request){

        $empId = $request->emp_id;
        $payrollMonth = $request->pay_month;
        $payrollYear = $request->pay_year;

        $schemeCode = -1;
        $nopayDivider = 0;
        $otDivider = 0;
        $epfEmployer = 0;
        $epfEmpoyee = 0;
        $etfEmployer = 0;
        $otEffect = 0;
        $nopayEffect = 0;
        $payeeEffect = 0;
        $attendanceFrom = 1;

        $noOfWorkingDays = 0;
        $noPayDays = 0;
        $late_Min = 0;
        $normalOT = 0;
        $doubleOT = 0;
        $sunDays = 0;
        $poyaDays = 0;

        $nopay_amount = 0;
        $normalOT_Amount = 0;
        $doubleOT_Amount = 0;
        $totalAllowances = 0;
        $totalDeductions = 0;

        $empNo = '';


        //Get assign scheme of the employee
        //====================================
        $AssignScheme = EmployeeScheme::where('EmployeeCode',$empId)->get();

        foreach($AssignScheme as $ascheme){
            $schemeCode = $ascheme->SchemCode;
        }

        // Get scheme settings to process payroll
        // ========================================
        $paySchemeValues = PayScheme::where('id',$schemeCode)->get();

        foreach($paySchemeValues as $paySchemeValue){

            $nopayDivider = $paySchemeValue->NOPAYDivider;
            $otDivider = $paySchemeValue->OTDivider;
            $epfEmployer = $paySchemeValue->EPFEmployer;
            $epfEmpoyee = $paySchemeValue->EPFEmployee;
            $etfEmployer = $paySchemeValue->ETFEmployeer;
            $otEffect = $paySchemeValue->OTEffect;
            $nopayEffect = $paySchemeValue->NopayEffect;
            $payeeEffect = $paySchemeValue->PayeeEffect;

        }

        // Get basic salary of the employee
        // ===================================

        $basicSalary = 0;
        $isEmployeeEligbleOT = 0;

        $employeesInfo = Employee::where("id",$empId)->get();

        foreach($employeesInfo as $employeeInfo){

            $basicSalary = $employeeInfo->basic_salary;
            $isEmployeeEligbleOT = $employeeInfo->eligibility_for_ot;
            $empNo = $employeeInfo->employee_number;

        }

        if($basicSalary<=0){
            $data = ["ECODE" => "ERRB", "EMPNO" => $empNo] ;
            echo json_encode($data);
            return;
        }

        // Read Attendance of the employee in given payroll month and year
        // ===============================================================
        $attendanceSummaries = AttendanceSummary::where("employeeId",$empId)
                             ->where('attendance_month',$payrollMonth)
                             ->where('attendance_year',$payrollYear)->get();

        if(Count($attendanceSummaries) == 0){
            $data = ["ECODE" => "ERRA", "EMPNO" => $empNo] ;
            echo json_encode($data);
            return;
        }
        foreach($attendanceSummaries as $attenSummaryValue){

            $noOfWorkingDays = $attenSummaryValue->work_days;
            $noPayDays = $attenSummaryValue->nopay_days;
            $late_Min = $attenSummaryValue->late_min;
            $normalOT = $attenSummaryValue->not;
            $doubleOT = $attenSummaryValue->dot;
            $sunDays = $attenSummaryValue->work_sundays;
            $poyaDays = $attenSummaryValue->work_poya;

        }



        // Insert basic salary
        // ====================
        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"Basic Salary"],
            ['item_value'=>$basicSalary,'item_type'=>"A"]
        );

        // Calculate nopay of the employee
        // ===============================

        if(($noPayDays > 0) && ($nopayEffect == 1)){

            $daySalary = ($basicSalary / $nopayDivider);
            $nopay_amount = $daySalary * $noPayDays;
        }

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"No Pay"],
            ['item_value'=>$nopay_amount,'item_type'=>"D"]
        );

        $epfetfEffectSalary = $basicSalary - $nopay_amount;

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"EPF Effect Salary"],
            ['item_value'=>$epfetfEffectSalary,'item_type'=>"A"]
        );

        // Calculate OT
        // ============
        if(($otEffect == 1) && ($isEmployeeEligbleOT == 1)){

            $hourRate = $basicSalary/$otDivider;


            if($normalOT > 0){

                $normalOT_Amount = $hourRate * 1.5;

                $modelPayroll = payroll::updateOrCreate(
                    ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"OT 1.5"],
                    ['item_value'=>$normalOT_Amount,'item_type'=>"A"]
                );

            }

            if($doubleOT > 0){
                $doubleOT_Amount = $hourRate * 2;

                $modelPayroll = payroll::updateOrCreate(
                    ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"OT 2"],
                    ['item_value'=>$doubleOT_Amount,'item_type'=>"A"]
                );
            }

        }
        // ================================

        // Fixed Allowance Calculation
        // =================================

        $modelFixedAllowances = DB::table("salary_entry")
                               ->join('transaction_assign','salary_entry.id','=','transaction_assign.TransactionCode')
                               ->where('salary_entry.EntryType',1)
                               ->where('salary_entry.VariableType',1)
                               ->where('transaction_assign.EmployeeCode',$empId)
                               ->select('salary_entry.EntryName','transaction_assign.Amount')
                               ->get();

        foreach($modelFixedAllowances as $modelFixedAllowance){

            $allowanceName = $modelFixedAllowance->EntryName;
            $allowanceAmount = $modelFixedAllowance->Amount;

            $totalAllowances += $allowanceAmount;

            $modelPayroll = payroll::updateOrCreate(
                ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>$allowanceName],
                ['item_value'=>$allowanceAmount,'item_type'=>"A"]
            );

        }
        // ===========================================================

        // Variable Allowance Calculation
        // ===============================
        $modelVariableAllowances = DB::table("salary_entry")
                                    ->join('transaction_assign','salary_entry.id','=','transaction_assign.TransactionCode')
                                    ->where('salary_entry.EntryType',1)
                                    ->where('salary_entry.VariableType',2)
                                    ->where('transaction_assign.EmployeeCode',$empId)
                                    ->where('transaction_assign.TransactionMonth',$payrollMonth)
                                    ->where('transaction_assign.TransactionYear',$payrollYear)
                                    ->select('salary_entry.EntryName','transaction_assign.Amount')
                                    ->get();

        foreach($modelVariableAllowances as $modelVariableAllowance){

            $allowanceVName = $modelVariableAllowance->EntryName;
            $allowanceVAmount = $modelVariableAllowance->Amount;

            $totalAllowances += $allowanceVAmount;

            $modelPayroll = payroll::updateOrCreate(
                ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>$allowanceVName],
                ['item_value'=>$allowanceVAmount,'item_type'=>"A"]
            );

        }

        // ===========================================================

        // Calculate gross salary
        // ========================
        $grossSalary = $epfetfEffectSalary + $normalOT_Amount + $doubleOT_Amount + $totalAllowances;

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"Gross Salary"],
            ['item_value'=>$grossSalary,'item_type'=>"A"]
        );

        // Calculate EPF / ETF %
        // ================

        $employeeContribution = ($epfetfEffectSalary / 100) * $epfEmpoyee;
        $employerContribution = ($epfetfEffectSalary / 100) * $epfEmployer;
        $etfContribution = ($epfetfEffectSalary / 100) * $etfEmployer;

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"EPF 8%"],
            ['item_value'=>$employeeContribution,'item_type'=>"D"]
        );

        $totalDeductions += $employeeContribution;

        // ===========================================================================

        // Fixed Deductions Calculation
        // =================================

        $modelFixedDeductions = DB::table("salary_entry")
                               ->join('transaction_assign','salary_entry.id','=','transaction_assign.TransactionCode')
                               ->where('salary_entry.EntryType',2)
                               ->where('salary_entry.VariableType',1)
                               ->where('transaction_assign.EmployeeCode',$empId)
                               ->select('salary_entry.EntryName','transaction_assign.Amount')
                               ->get();

        foreach($modelFixedDeductions as $modelFixedDeduction){

            $fDeductionName = $modelFixedDeduction->EntryName;
            $fDeductionAmount = $modelFixedDeduction->Amount;

            $totalDeductions += $fDeductionAmount;

            $modelPayroll = payroll::updateOrCreate(
                ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>$fDeductionName],
                ['item_value'=>$fDeductionAmount,'item_type'=>"D"]
            );

        }
        // ===========================================================

        // Variable Deductions Calculation
        // =================================

        $modelVariableDeductions = DB::table("salary_entry")
                               ->join('transaction_assign','salary_entry.id','=','transaction_assign.TransactionCode')
                               ->where('salary_entry.EntryType',2)
                               ->where('salary_entry.VariableType',2)
                               ->where('transaction_assign.EmployeeCode',$empId)
                               ->where('transaction_assign.TransactionMonth',$payrollMonth)
                               ->where('transaction_assign.TransactionYear',$payrollYear)
                               ->where('transaction_assign.EmployeeCode',$empId)
                               ->select('salary_entry.EntryName','transaction_assign.Amount')
                               ->get();

        foreach($modelVariableDeductions as $modelVariableDeduction){

            $vDeductionName = $modelVariableDeductions->EntryName;
            $vDeductionAmount = $modelVariableDeductions->Amount;

            $totalDeductions += $vDeductionAmount;

            $modelPayroll = payroll::updateOrCreate(
                ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>$vDeductionName],
                ['item_value'=>$vDeductionAmount,'item_type'=>"D"]
            );

        }
        // ===========================================================

        // PAYEE TAX Calculator
        // =============================
        $totalTaxAmount = 0;

        $taxAmount1 = 0;
        $taxAmount2 = 0;
        $taxAmount3 = 0;
        $taxAmount4 = 0;
        $taxAmount5 = 0;
        $taxAmount6 = 0;

        $Block1 = 0;
        $Block2 = 0;
        $Block3 = 0;
        $Block4 = 0;
        $Block5 = 0;
        $Block6 = 0;

        if($grossSalary > 100000){

            $taxableSalary = $grossSalary - 100000;


            if($taxableSalary > 41666.67){

                $Block2 = $taxableSalary - 41666.67;
                $taxAmount1 = 41666.67 * 0.06;

            }else{
                $taxAmount1 = $taxableSalary * 0.06;
            }


            if($Block2 > 41666.67){

                $Block3 = $Block2 - 41666.67;
                $taxAmount2 = 41666.67 * 0.12;

            }else{
                $taxAmount2 = $Block2 * 0.12;
            }

            if($Block3 > 41666.67){

                $Block4 = $Block3 - 41666.67;
                $taxAmount3 = 41666.67 * 0.18;

            }else{
                $taxAmount3 = $Block3 * 0.18;
            }

            if($Block4 > 41666.67){

                $Block5 = $Block4 - 41666.67;
                $taxAmount4 = 41666.67 * 0.24;

            }else{
                $taxAmount4 = $Block4 * 0.24;
            }

            if($Block5 > 41666.67){
                $Block6 = $Block5 - 41666.67;
                $taxAmount5 = 41666.67 * 0.3;
            }else{
                $taxAmount5 = $Block5 * 0.3;
            }

            if($Block6 > 0){ $taxAmount6 = $Block6 * 0.36; }
        }

        $totalTaxAmount = $taxAmount1 + $taxAmount2 + $taxAmount3 + $taxAmount4 + $taxAmount5 + $taxAmount6;

        if($totalTaxAmount > 0){

            $totalDeductions += $totalTaxAmount;

            $modelPayroll = payroll::updateOrCreate(
                ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"PAYEE"],
                ['item_value'=>$totalTaxAmount,'item_type'=>"D"]
            );
        }

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"Total Deductions"],
            ['item_value'=>$totalDeductions,'item_type'=>"D"]
        );

        // Calculate net salary
        // ====================
        $netSalary = 0;

        $netSalary = $grossSalary - $totalDeductions;

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"Net Salary"],
            ['item_value'=>$netSalary,'item_type'=>"D"]
        );
        // =========================================================

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"EPF 12%"],
            ['item_value'=>$employerContribution,'item_type'=>"A"]
        );

        $modelPayroll = payroll::updateOrCreate(
            ['employeeId'=>$empId, 'payMonth'=>$payrollMonth, 'payYear'=>$payrollYear,'item_name'=>"ETF 3%"],
            ['item_value'=>$etfContribution,'item_type'=>"A"]
        );

        return response()->json($modelPayroll);

    }

}
