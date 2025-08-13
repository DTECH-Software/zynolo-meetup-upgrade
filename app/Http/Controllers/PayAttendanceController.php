<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\AttendanceSummary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayAttendanceController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.payroll.payattendancesummary');
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

    public function SaveAttendance(Request $request){

        try{

            DB::beginTransaction();

            //$employee_id = \Illuminate\Support\Facades\Auth::user()->employee_id;
            /*
            $modelAttendanceSummary = new AttendanceSummary();

            $modelAttendanceSummary->employeeId = $request->input('empcode');
            $modelAttendanceSummary->attendance_month = $request->input('attMonth');
            $modelAttendanceSummary->attendance_year = $request->input('attYear');
            $modelAttendanceSummary->work_days = $request->input('noOfWorkingDays');
            $modelAttendanceSummary->nopay_days = $request->input('noOfNopayDays');
            $modelAttendanceSummary->late_min = $request->input('lateMin');
            $modelAttendanceSummary->late_days = $request->input('lateDays');
            $modelAttendanceSummary->not = $request->input('normalOTHrs');
            $modelAttendanceSummary->not_days = $request->input('notmalOTDays');
            $modelAttendanceSummary->dot = $request->input('DOTHrs');
            $modelAttendanceSummary->dot_days = $request->input('DOTDays');
            $modelAttendanceSummary->work_sundays = $request->input('sundays');
            $modelAttendanceSummary->work_poya = $request->input('poyadays');

            $modelAttendanceSummary->save();
            */

            $modelAttendanceSummary = AttendanceSummary::updateOrCreate(
                ['employeeId'=>$request->input('empcode'),'attendance_month'=>$request->input('attMonth'),'attendance_year'=>$request->input('attYear')],
                ['work_days'=>$request->input('noOfWorkingDays'),'nopay_days'=>$request->input('noOfNopayDays'),'late_min'=>$request->input('lateMin'),
                 'late_days'=>$request->input('lateDays'),'not'=>$request->input('normalOTHrs'),'not_days'=>$request->input('notmalOTDays'),
                 'dot'=>$request->input('DOTHrs'),'dot_days'=>$request->input('DOTDays'),'work_sundays'=>$request->input('sundays'),
                 'work_poya'=>$request->input('poyadays')]
            );

            self::Log('Attendance Saved', 'Pass');

            DB::commit();


        }catch (\Exception $e) {

            DB::rollBack();
            self::Log($e->getMessage(), 'Fail');

            return back()->with('error', $e->getMessage());
        }
    }

    public function GetAttendanceById(Request $request){

        $modelAttendanceSummary = AttendanceSummary::where('employeeId',$request->input('empcode'))
                                  ->where('attendance_month',$request->input('attMonth'))
                                  ->where('attendance_year',$request->input('attYear'))
                                  ->get();

        return $modelAttendanceSummary;

    }
}
