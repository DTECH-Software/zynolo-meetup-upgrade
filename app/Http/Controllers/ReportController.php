<?php

namespace App\Http\Controllers;

use App\Models\payroll;
use App\Models\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PDF;

class ReportController extends Controller
{
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

    public function ViewPayslip(){
        return view('pages.payroll.payslip');
    }

    public function GeneratePayslip(Request $request){

        //ini_set('max_execution_time', 300);

        $emplist = $request->query('emplist');
        $payMonth = $request->query('pmon');
        $payYear = $request->query('pyear');

        $empArrayList = str_split($emplist);
        $payDetails = [];

        $empinfos = DB::table('payroll')
                    ->join('employees','employees.id','=','payroll.employeeid')
                    ->join('designations','employees.current_designation','=','designations.id')
                    ->select('employees.first_name','employees.last_name','employees.employee_number','designations.name','payroll.payMonth','payroll.payYear','employees.id')
                    ->whereIn('employees.id', $empArrayList)
                    ->where('payroll.payMonth','=',$payMonth)
                    ->where('payroll.payYear','=',$payYear)
                    ->distinct()
                    ->get();

        foreach($empinfos as $empInfo){


            $slipHeader[] = [
                'first_name' => $empInfo->first_name,
                'last_name' => $empInfo->last_name,
                'employee_number' => $empInfo->employee_number,
                'payMonth' => $empInfo->payMonth,
                'payYear' => $empInfo->payYear,
                'designame' => $empInfo->name,
            ];


            $payInformation = DB::table('payroll')
                            ->join('employees','employees.id','=','payroll.employeeid')
                            ->join('designations','employees.current_designation','=','designations.id')
                            ->select('payroll.item_name','payroll.item_value','payroll.item_type')
                            ->where('employees.id', '=', $empInfo->id)
                            ->where('payroll.payMonth','=',$payMonth)
                            ->where('payroll.payYear','=',$payYear)
                            ->get();

            foreach($payInformation as $payInfor){

                $slipDetails[] = [
                    'item_name' => $payInfor->item_name,
                    'item_value' => $payInfor->item_value,
                    'item_type' => $payInfor->item_type,
                ];
            }

            $payDetails[] = [

                'header'=> $slipHeader,
                'details' => $slipDetails,
            ];

            $slipHeader = [];
            $slipDetails = [];
        }



        /*
        $empPayInfos = DB::table('payroll')
                        ->join('employees','employees.id','=','payroll.employeeid')
                        ->join('designations','employees.current_designation','=','designations.id')
                        ->select('payroll.item_name','payroll.item_value','payroll.item_type')
                        ->whereIn('employees.id', $empArrayList)
                        ->where('payroll.payMonth','=',$payMonth)
                        ->where('payroll.payYear','=',$payYear)
                        ->get()->toArray();
        */

        //$mergedArray = array_merge($empinfos,$empPayInfos);

        //$jsonFormat = response()->json($payDetails);
        //echo $jsonFormat;
        //$jsonFormat = json_decode($jsonFormat, true);


        //return view('pages.payroll.rptslip',['empinfos'=>$empinfos, 'payInfors'=>$empPayInfos]);
        return view('pages.payroll.rptslip',['empinfos'=>$payDetails]);

    }


}
