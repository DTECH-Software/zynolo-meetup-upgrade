<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Imports\AttendanceImport;
use App\Jobs\SendBulkEmail;
use App\Mail\InvalidAttendance;
use App\Models\Attendance;
use App\Models\AttendanceAppeals;
use App\Models\AttendanceTemp;
use App\Models\Day;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\ShiftDetail;
use App\Models\ShiftType;
use App\Models\ShortLeave;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Shift Administration');

        return view('pages.shifts.spreadsheets');
    }







    public function leaveAppealsStore(Request $request)
    {
//        dd($request);
        try {
           DB::beginTransaction();
            $attendance_appeals = new AttendanceAppeals();

            if(isset($request->appeal_type)){
                $attendance_appeals->appeal_type =$request->appeal_type;
            }else{
                $attendance_appeals->appeal_type = 'both';
            }
            $attendance_appeals->in_time = $request->in_time;
            $attendance_appeals->date = $request->date;
            $attendance_appeals->attendance_id = $request->attendance_id;
            $attendance_appeals->out_time = $request->out_time;
            $attendance_appeals->reason = $request->reason;
            $attendance_appeals->employee_id = $request->employee_id;
            $attendance_appeals->no_of_hours = $request->no_of_hours;
            $attendance_appeals->adjusted_in_time = $request->adjusted_in_time;
            $attendance_appeals->adjusted_out_time = $request->adjusted_out_time;
            $attendance_appeals->save();

            $employee= Employee::find($request->employee_id);
            $reciver= User::find($employee->reporting_person_id);;
            $message = "{$employee->known_name} has submitted an appeal for {$request->date}. Click below to review.";
            Mail::to($reciver->email)->send(new \App\Mail\hr_mail($message));

          DB::commit();

//            $today = Carbon::now();
//            $startOfMonth = $today->copy()->startOfMonth();
//            $endOfMonth = $today->copy()->endOfMonth();
//
//            if (Auth::user()->hasRole('Super Admin') || Auth::user()->can('View All Attendance Data')) {
//                $data['attendance_data'] = Attendance::with('employees')
//                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
//                    ->get();
//            } elseif(Auth::user()->hasRole(['HOD'])){
//
//                $employees = Employee::with('departments')
//                    ->where('status', '=', 'ACTIVE')
//                    ->where(function($query) {
//                        $query->where('reporting_person_id', Auth::user()->id)
//                            ->orWhere('id', Auth::user()->employee_id);
//                    })
//                    ->orderByRaw("id = ? DESC", [Auth::user()->employee_id])
//                    ->orderBy('id')
//                    ->get();
//
//                $employee_ids = $employees->pluck('id');
//
//                $data['attendance_data'] = Attendance::with('employees')
//                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
//                    ->whereIn('employee_id', $employee_ids)
//                    ->get();
//            }else {
//                $data['attendance_data'] = Attendance::where('employee_id', Auth::user()->employee_id)
//                    ->with('employees')
//                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
//                    ->get();
//            }
//            $data['message'] = 'Appealed Successfully !';
//            return view('pages.attendance_reports.view-time-sheet')->with($data);

            self::Log('Appealed Successfully !', 'Pass');

          return redirect()->route('attendance.index')->with('message','Appealed Successfully !');
        }catch (\Exception $e) {
            DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return redirect()->route('attendance.index')->with('error',$e->getMessage());

        }

    }

    public function viewAppeals()
    {
        $user = Auth::user();
        $employees =  Employee::where('reporting_person_id', $user->id)->get();
        $appeal_lists = [];

        $all_appeals = [];

        foreach ($employees as $employee) {
            $appeal_list = AttendanceAppeals::where('employee_id', $employee->id)->where('status','REQUESTED')->with('employee')->get();
            if (count($appeal_list)>0){
                $appeal_lists[] = $appeal_list;
            }
        }

        foreach ($employees as $employee) {
            $appeal_list = AttendanceAppeals::where('employee_id', $employee->id)->where('status','ACCEPTED')->Orwhere('status','REJECTED')->with('employee')->get();
            if (count($appeal_list)>0){
                $all_appeals[] = $appeal_list;
            }
        }





        $data['appeal_lists'] = $appeal_lists;
        $data['all_appeals'] = $all_appeals;
//        dd($appeal_lists);
        return view('pages.leaves.review-appeals')->with($data);

    }

    public function ReviewAppeals(Request $request){
//      dd($request->status);
        try {
//            DB::beginTransaction();
            if($request->status == 'APPROVED'){
                $id = $request->id;
                $status = $request->status;
                $attendance = Attendance::find($request->attendance_id);
//                dd($attendance);
                if(isset($request->adjusted_in_time) && isset($request->adjusted_out_time)){
                    $attendance->in_time = $request->adjusted_in_time;
                    $attendance->out_time = $request->adjusted_out_time;

                    $attendance->adjusted_in_time = $request->adjusted_in_time;
                    $attendance->adjusted_out_time = $request->adjusted_out_time;
                } elseif(isset($request->adjusted_in_time)){
                    $attendance->in_time = $request->adjusted_in_time;
                    $attendance->out_time = $request->out_time;
                    $attendance->adjusted_in_time = $request->adjusted_in_time;
                } elseif(isset($request->adjusted_out_time)){
                    $attendance->in_time = $request->in_time;
                    $attendance->out_time = $request->adjusted_out_time;
                    $attendance->adjusted_out_time = $request->adjusted_out_time;
                } else {
                    $attendance->in_time = $request->in_time;
                    $attendance->out_time = $request->out_time;
                }



                $attendance->reason = $request->reason;

                $attendance->status = 'VALID';
                $min_time =$attendance->in_time;
                $max_time = $attendance->out_time;
                $date = $request->date;
                $employee_shift = EmployeeShift::where('employee_id',$request->employee_id)->first();
                $shift_id = $employee_shift->shift_id;
                $shift_type = ShiftType::find($shift_id);
                $day_details= Day::where('date',$request->date)->first();
                $shift_details = ShiftDetail::where('shift_type_id',$shift_type->id)->where('day_type_id',$day_details->holiday_type_id)->first();
                $employee= Employee::find($request->employee_id);
                $message=1;

                $this->inTimeOutTimeRecordedValidation($min_time, $max_time, $shift_details, $date, $attendance, $employee,$message);

                $attendance->not = (double)$attendance->not + (double)$request->no_of_hours;
                $attendance->late_appeal_status = 'ACCEPTED';
//                dd($request);
                $attendance->save();
                $appeals = AttendanceAppeals::find($id);
                $appeals->status = 'ACCEPTED';
                $appeals->save();


                $mess= "Your appeal has approved for the date of  {$date}.";
                Mail::to($employee->email)->send(new \App\Mail\hr_mail($mess));



            }else{
                $appeals = AttendanceAppeals::find($request->id);

                $attendance = Attendance::find($request->attendance_id);
//                dd($request->attendance_id);
                $attendance->late_appeal_status = 'DECLINED';
                $appeals->status = $request->status;
                $appeals->save();

                $employee= Employee::find($request->employee_id);
                $mess= "Your appeal has rejected for the date of  {$request->date}. {$request->review}";
                Mail::to($employee->email)->send(new \App\Mail\hr_mail($mess));
            }
            self::Log('Appeal Rejected', 'Pass');

            DB::commit();
            return response()->json(['message' => 'Success']);
        }catch (\Exception $e){
            DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return response()->json(['error' => $e->getMessage()]);

        }


    }







    public function handleAttendance(Request $request)
    {
        if(isset($request->attendance_sheet)) {

            try {
                $date = $request->attendance_date;

                Excel::import( new AttendanceImport($date), $request->file('attendance_sheet'));

                $employees = Employee::where('status','ACTIVE')->get();;
                DB::beginTransaction();
                $message =1;
                foreach ($employees as $employee){
//                    $attendanceTemp =  AttendanceTemp::where('date',$date)->get();
                    $employee_shift = EmployeeShift::where('employee_id', $employee->id)->first();
                    $shift_id = $employee_shift->shift_id;
                    $shift_type = ShiftType::find($shift_id);




                    $attendance = new Attendance();

                        $attendance->employee_id = $employee->id;

                        $attendance->shift_id = $shift_id;
                        $attendance->date = $date;

                        //check if it is a holiday in days --> passed
                        //check if it is a holiday in shift--> passed --> then it is a holiday
                        //                                 -->fail  --> then it is a working day
                        $day_details= Day::where('date',$attendance->date)->first();


                        $shift_details = ShiftDetail::where('shift_type_id',$shift_type->id)->where('day_type_id',$day_details->holiday_type_id)->first();
                        if($shift_details == null){
                            continue;
                        }
                        $attendance->is_day_expected = $shift_details->day_expected;


                    $attendance_data = AttendanceTemp::where('fingerprint_id', $employee->fingerprint_id)
                        ->where('date', $date)
                        ->get();


//                    dd($employee->id,$attendance_data,$min_time,$max_time);

//                    dd($attendance_data,$employee->id);

//                    $maxTime = AttendanceTemp::where('employee_id', $employee->id)
//                        ->where('date', $date)
//                        ->max('time');



                    // here the date is provided date when uploading the sheet
                    if ($attendance_data->count() == 0 ) {
                        //if in time and out time isn't set and not applied/approved a leave , then it is an invalid leave
                        $is_applied = Leave::where('date',$date)->where('employee_id',$employee->id)->where('type','FULL_DAY')->first();


                        if ($is_applied == null) {
                            $attendance->status = 'INVALID';
                            $this->sendMail($date,$employee,"Invalid Attendance");
                            //updates if leave isn't applied
                            //should send a mail
                        }else{
                            $attendance->leave_types_id_1 = $is_applied->leave_type_id;
                            $attendance->is_day_expected = 'false';
                            //updates if leave applied
                        }
                        $attendance->in_time = null;
                        $attendance->out_time = null;
                        $attendance->in_not = null;
                        $attendance->out_not = null;
                        $attendance->not = null;
                        $attendance->dot = null;
                        $attendance->in_late = null;
                        $attendance->out_late = null;
                        $attendance->late_min = null;
//                        $attendance->is_day_expected = false;
//                        return back()->with('error','Attendance Data Retrieving Error');
                       //here type the logic to check if employee has applied for holidays





                    } else {

                        $min_time = $attendance_data->min('time');
                        $max_time = $attendance_data->max('time');

                        $attendance->in_time = $min_time;
                        $attendance->clock_in_time = $min_time;
                        $attendance->out_time = $max_time;
                        $attendance->clock_out_time = $max_time;
//in time out time recorded validation
                        $this->inTimeOutTimeRecordedValidation($min_time, $max_time, $shift_details, $date, $attendance, $employee,$message);

                }
                    $attendance->save();

                    $shortLeaves = ShortLeave::where('date',$date)->where('employee_id',$employee->id)->where('status','APPROVED')->get();

                    if (count($shortLeaves)>0){
                        $leave_controller = new LeaveController();
                        foreach ($shortLeaves as $shortLeave){
                            $leave_controller->process_shortleaves($shortLeave,$attendance);
                        }
                        $attendance->save();
                    }

                }
                DB::commit();
                self::Log('Attendance Updated Successfully', 'Pass');
                return back()->with('message','Attendance Updated Successfully');

            }catch (\Exception $e) {
                self::Log($e->getMessage(), 'Fail');
                DB::rollBack();
                return back()->with('error', 'Error Uploading Attendance , Make Sure All Employees are assigned to shifts !');
            }

        }




    }




//    public function handleAttendance(Request $request)
//    {
//        if(isset($request->attendance_sheet)) {
//
//            try {
//                $date = $request->attendance_date;
//
//                Excel::import( new AttendanceImport($date), $request->file('attendance_sheet'));
//
//                $employees = Employee::where('status','ACTIVE')->get();;
//                DB::beginTransaction();
//                $message =1;
//                foreach ($employees as $employee){
////                    $attendanceTemp =  AttendanceTemp::where('date',$date)->get();
//                    $employee_shift = EmployeeShift::where('employee_id', $employee->id)->first();
//                    $shift_id = $employee_shift->shift_id;
//                    $shift_type = ShiftType::find($shift_id);
//
//
//
//
//                    $attendance = new Attendance();
//
//                    $attendance->employee_id = $employee->id;
//
//                    $attendance->shift_id = $shift_id;
//                    $attendance->date = $date;
//
//                    //check if it is a holiday in days --> passed
//                    //check if it is a holiday in shift--> passed --> then it is a holiday
//                    //                                 -->fail  --> then it is a working day
//                    $day_details= Day::where('date',$attendance->date)->first();
//
//
//                    $shift_details = ShiftDetail::where('shift_type_id',$shift_type->id)->where('day_type_id',$day_details->holiday_type_id)->first();
//                    if($shift_details == null){
//                        continue;
//                    }
//                    $attendance->is_day_expected = $shift_details->day_expected;
//
//
//                    $attendance_data = AttendanceTemp::where('fingerprint_id', $employee->fingerprint_id)
//                        ->where('date', $date)
//                        ->get();
//
//
////                    dd($employee->id,$attendance_data,$min_time,$max_time);
//
////                    dd($attendance_data,$employee->id);
//
////                    $maxTime = AttendanceTemp::where('employee_id', $employee->id)
////                        ->where('date', $date)
////                        ->max('time');
//
//
//
//                    // here the date is provided date when uploading the sheet
//                    if ($attendance_data->count() == 0 ) {
//                        //if in time and out time isn't set and not applied/approved a leave , then it is an invalid leave
//                        $is_applied = Leave::where('date',$date)->where('employee_id',$employee->id)->where('type','FULL_DAY')->first();
//
//
//                        if ($is_applied == null) {
//                            $attendance->status = 'INVALID';
//                            $this->sendMail($date,$employee,"Invalid Attendance");
//                            //updates if leave isn't applied
//                            //should send a mail
//                        }else{
//                            $attendance->leave_types_id_1 = $is_applied->leave_type_id;
//                            $attendance->is_day_expected = 'false';
//                            //updates if leave applied
//                        }
//                        $attendance->in_time = null;
//                        $attendance->out_time = null;
//                        $attendance->in_not = null;
//                        $attendance->out_not = null;
//                        $attendance->not = null;
//                        $attendance->dot = null;
//                        $attendance->in_late = null;
//                        $attendance->out_late = null;
//                        $attendance->late_min = null;
////                        $attendance->is_day_expected = false;
////                        return back()->with('error','Attendance Data Retrieving Error');
//                        //here type the logic to check if employee has applied for holidays
//
//
//
//
//
//                    } else {
//
//                        $min_time = $attendance_data->min('time');
//                        $max_time = $attendance_data->max('time');
//
//                        $attendance->in_time = $min_time;
//                        $attendance->clock_in_time = $min_time;
//                        $attendance->out_time = $max_time;
//                        $attendance->clock_out_time = $max_time;
////in time out time recorded validation
//                        $this->inTimeOutTimeRecordedValidation($min_time, $max_time, $shift_details, $date, $attendance, $employee,$message);
//
//                    }
//                    $attendance->save();
//
//                    $shortLeaves = ShortLeave::where('date',$date)->where('employee_id',$employee->id)->where('status','APPROVED')->get();
//
//                    if (count($shortLeaves)>0){
//                        $leave_controller = new LeaveController();
//                        foreach ($shortLeaves as $shortLeave){
//                            $leave_controller->process_shortleaves($shortLeave,$attendance);
//                        }
//                        $attendance->save();
//                    }
//
//                }
//                DB::commit();
//                return back()->with('message','Attendance Updated Successfully');
//            }catch (\Exception $e) {
//                DB::rollBack();
//                return back()->with('error', 'Error Uploading Attendance , Make Sure All Employees are assigned to shifts !');
//            }
//
//        }
//
//
//
//
//    }


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

    /**
     * @param $min_time
     * @param $max_time
     * @param $shift_details
     * @param mixed $date
     * @param Attendance $attendance
     * @param mixed $employee
     * @return void
     */
    public function inTimeOutTimeRecordedValidation($min_time, $max_time, $shift_details, mixed $date, Attendance $attendance, mixed $employee,$message): void
    {
        $in_time = Carbon::parse($min_time);
        $out_time = Carbon::parse($max_time);
//                    dd($in_time,$out_time);

        $shift_in_time = Carbon::parse($shift_details->in_time);
        $shift_out_time = Carbon::parse($shift_details->out_time);
        $shift_half_day_in_time = Carbon::parse($shift_details->half_day_in_time);
        $shift_half_day_out_time = Carbon::parse($shift_details->half_day_out_time);


        $absoluteDiff = abs($in_time->diffInMinutes($out_time));

        $min_diff = 5;





        //handle leaves

        if ($in_time->equalTo($out_time) || $absoluteDiff <= $min_diff) {
            $in_time_diff = $in_time->diffInMinutes($shift_in_time);
            $out_time_diff = $out_time->diffInMinutes($shift_out_time);

//                        dd($shift_details->midnight_crossover);
            if ($shift_details->midnight_crossover === 'true') {
                if ($out_time_diff > $in_time_diff) {
                    $out_time = '';
                } else {
                    $today_date = Carbon::parse($date);
                    $yesterday = $today_date->subDay();
                    $attendance_day = Attendance::where('date', $yesterday)->first();
                    if ($attendance_day) {
                        $attendance_day->out_time = $out_time;
                        $attendance_day->out_date = $date;
                    } else {
                        $attendance_day->status = 'INVALID';
                        $this->sendMail($date,$employee,"Invalid Attendance");
                    }
                }

            } else {
//                            dd($in_time_diff,$out_time_diff);
                if ($in_time_diff < $out_time_diff) {
                    $attendance->out_time = null;
                } else {
                    $attendance->in_time = null;
                }
                $attendance->status = 'INVALID';
                $this->sendMail($date,$employee,"Invalid Attendance");
                //need a logic to send a mail here
            }
        } else {

            //half day limit times also should be checked using half day

            if($in_time>$shift_in_time){
                if($shift_in_time->diffInMinutes($in_time)>(int)$shift_details->late_grace_min){
                    $in_late = $shift_in_time->diffInMinutes($in_time)-(int)$shift_details->late_grace_min ;
                    $this->sendMail($date,$employee,'Late attendance');
                    $attendance->status = 'LATE';
                }else{
                    $in_late = 0;
                }

            }else{
                $in_late = 0;
            }


            //morning half day check
            if ($in_time >= $shift_half_day_in_time) {
                $is_half_day_taken = Leave::where('date', $date)
                    ->where('employee_id', $employee->id)
                    ->where('type', 'MORNING_HALF_DAY')
                    ->first();

                if (is_null($is_half_day_taken)) {
                    $attendance->status = 'LATE';
                    $this->sendMail($date,$employee,"Significant Delay in Attendance");

                } else {
                    $attendance->leave_types_id_2 = $is_half_day_taken->leave_type_id;
                }
            }

            //evening half day check
            if ($shift_half_day_out_time >= $out_time) {
                $is_half_day_taken = Leave::where('date', $date)
                    ->where('employee_id', $employee->id)
                    ->where('type', 'EVENING_HALF_DAY')
                    ->first();

                if (is_null($is_half_day_taken)) {
                    $attendance->status = 'EARLY_DEPARTURE';
                    $this->sendMail($date,$employee,'Significant Early Departure Notice');

                } else {
                    $attendance->leave_types_id_2 = $is_half_day_taken->leave_type_id;
                }

            }

            if($out_time < $shift_out_time && $out_time > $shift_half_day_out_time){
                $out_late = $out_time->diffInMinutes($shift_out_time);
                $this->sendMail($date,$employee,'Early Departure');
                $attendance->status = 'EARLY_DEPARTURE';
            }else{
                $out_late = 0;
            }


            // if leave is taken and still came to work then reverse the leaves even if it is approved
            $is_leave_taken = Leave::where('date', $date)
                ->where('employee_id', $employee->id)
                ->where('type', 'FULL_DAY')
                ->first();

            if ($is_leave_taken) {
                $allocated_leaves = LeaveAllocation::where('employee_id', $employee->id)
                    ->where('leave_type_id', $is_leave_taken->leave_type_id)
                    ->get();


                // Check if any allocation exists
                if ($allocated_leaves->isNotEmpty()) {
                    // Update allocated_leaves attribute
                    $allocated_leavesInstance = $allocated_leaves->first();
                    $allocated_leavesInstance->used_leaves -= 1;
                    $allocated_leavesInstance->save();
                }

                // Cancel the leave
                $is_leave_taken->status = 'CANCELLED';
                $is_leave_taken->save();
            }





//EARLY_DEPARTURE , LATE
// Calculate overtime and rounded overtime


            if ($employee->eligibility_for_ot == 1) {

                $roundingStrategy = env('OVERTIME_ROUNDING_STRATEGY', 'up');
                $minimumOvertimeHours = env('OVERTIME_GRACE_HOURS', 0) * 60; //need to discuss with consultant
                $overtimeDoubleHours = env('OVERTIME_DOUBLE_RATE_HOURS', 0) * 60;

//                            dd($minimumOvertimeHours,$overtimeDoubleHours);

                //calculate overtime hours
//                $in_not_not_r = $shift_in_time->greaterThan($in_time) ? max(0, $in_time->diffInMinutes($shift_in_time)) : 0;

                if($shift_in_time>$in_time){
                    $in_not_not_r = max(0, $in_time->diffInMinutes($shift_in_time));
                }else{
                    $in_not_not_r = 0;
                }

//                $out_not_not_r = $out_time->greaterThan($shift_out_time) ? max(0, $out_time->diffInMinutes($shift_out_time)) : 0;

                if($out_time >$shift_out_time){
                    $out_not_not_r = max(0, $out_time->diffInMinutes($shift_out_time))-(int)$shift_details->late_after;
                }else{
                    $out_not_not_r = 0;
                }
//                            if($employee->id == 17) {
//                                dd($in_not_not_r,$out_not_not_r);
//                            }
                if ($shift_details->is_in_ot_calculate == 'true') {
                    if (($in_not_not_r) > $minimumOvertimeHours) {
                        if ($overtimeDoubleHours != 0 && $in_not_not_r > $overtimeDoubleHours) {
                            $in_dot_not_r = $in_not_not_r - $overtimeDoubleHours;
//                             $in_dot_temp = $roundingStrategy === 'up' ? ceil($in_dot_not_r) : floor($in_dot_not_r);
                            $in_dot_temp = $in_dot_not_r;
                            $in_not = $overtimeDoubleHours;
                        } else {
//                             $in_not_temp = $roundingStrategy === 'up' ? ceil($in_not_not_r) : floor($in_not_not_r);
                            $in_dot_temp = 0;
                            $in_not = $in_not_not_r;
                        }
                    } else {
                        $in_not = 0;
                        $in_dot_temp = 0;
                    }

                } else {
                    $in_not = 0;
                    $in_dot_temp = 0;
                }

                if ($shift_details->is_out_ot_calculate == 'true') {
                    if (($out_not_not_r) > $minimumOvertimeHours) {
                        if ($overtimeDoubleHours != 0 && $out_not_not_r > $overtimeDoubleHours) {
                            $out_dot_not_r = $out_not_not_r - $overtimeDoubleHours;
//                             $out_dot_temp = $roundingStrategy === 'up' ? ceil($out_dot_not_r) : floor($out_dot_not_r);
                            $out_dot_temp = $out_dot_not_r;
                            $out_not = $overtimeDoubleHours;
                        } else {
//                             $out_not_temp = $roundingStrategy === 'up' ? ceil($out_not_not_r) : floor($out_not_not_r);
                            $out_dot_temp = 0;
                            $out_not = $out_not_not_r;
                        }
                    } else {
                        $out_not = 0;
                        $out_dot_temp = 0;
                    }

                } else {
                    $out_not = 0;
                    $out_dot_temp = 0;
                }


                $dot = $in_dot_temp + $out_dot_temp;
//                            $dot = 0;


                // Calculate total rounded overtime and late minutes
                $late_min = $in_late + $out_late;
                $not = $in_not + $out_not;
                // Update attendance object attributes
                $attendance->in_not = $in_not;
                $attendance->out_not = $out_not;
                $attendance->not = $not;
                $attendance->dot = $dot;
//::todo
            } else {
                $late_min = null;
                $attendance->in_not = null;
                $attendance->out_not = null;
                $attendance->not = null;
                $attendance->dot = null;
            }

            $attendance->in_late = $in_late;
            $attendance->out_late = $out_late;
            $attendance->late_min = $late_min;
        }
    }

//
//    /**
//     * @param $min_time
//     * @param $max_time
//     * @param $shift_details
//     * @param mixed $date
//     * @param Attendance $attendance
//     * @param mixed $employee
//     * @return void
//     */
//    public function inTimeOutTimeRecordedValidation($min_time, $max_time, $shift_details, mixed $date, Attendance $attendance, mixed $employee,$message): void
//    {
//        $in_time = Carbon::parse($min_time);
//        $out_time = Carbon::parse($max_time);
////                    dd($in_time,$out_time);
//
//        $shift_in_time = Carbon::parse($shift_details->in_time);
//        $shift_out_time = Carbon::parse($shift_details->out_time);
//        $shift_half_day_in_time = Carbon::parse($shift_details->half_day_in_time);
//        $shift_half_day_out_time = Carbon::parse($shift_details->half_day_out_time);
//
//
//        $absoluteDiff = abs($in_time->diffInMinutes($out_time));
//
//        $min_diff = 5;
//
//
//
//
//
//        //handle leaves
//
//        if ($in_time->equalTo($out_time) || $absoluteDiff <= $min_diff) {
//            $in_time_diff = $in_time->diffInMinutes($shift_in_time);
//            $out_time_diff = $out_time->diffInMinutes($shift_out_time);
//
////                        dd($shift_details->midnight_crossover);
//            if ($shift_details->midnight_crossover === 'true') {
//                if ($out_time_diff > $in_time_diff) {
//                    $out_time = '';
//                } else {
//                    $today_date = Carbon::parse($date);
//                    $yesterday = $today_date->subDay();
//                    $attendance_day = Attendance::where('date', $yesterday)->first();
//                    if ($attendance_day) {
//                        $attendance_day->out_time = $out_time;
//                        $attendance_day->out_date = $date;
//                    } else {
//                        $attendance_day->status = 'INVALID';
//                        $this->sendMail($date,$employee,"Invalid Attendance");
//                    }
//                }
//
//            } else {
////                            dd($in_time_diff,$out_time_diff);
//                if ($in_time_diff < $out_time_diff) {
//                    $attendance->out_time = null;
//                } else {
//                    $attendance->in_time = null;
//                }
//                $attendance->status = 'INVALID';
//                $this->sendMail($date,$employee,"Invalid Attendance");
//                //need a logic to send a mail here
//            }
//        } else {
//
//            //half day limit times also should be checked using half day
//
//            if($in_time>$shift_in_time){
//                if($shift_in_time->diffInMinutes($in_time)>(int)$shift_details->late_grace_min){
//                    $in_late = $shift_in_time->diffInMinutes($in_time)-(int)$shift_details->late_grace_min ;
//                    $this->sendMail($date,$employee,'Late attendance');
//                    $attendance->status = 'LATE';
//                }else{
//                    $in_late = 0;
//                }
//
//            }else{
//                $in_late = 0;
//            }
//
//
//            //morning half day check
//            if ($in_time >= $shift_half_day_in_time) {
//                $is_half_day_taken = Leave::where('date', $date)
//                    ->where('employee_id', $employee->id)
//                    ->where('type', 'MORNING_HALF_DAY')
//                    ->first();
//
//                if (is_null($is_half_day_taken)) {
//                    $attendance->status = 'INVALID';
//                    $this->sendMail($date,$employee,"Invalid Attendance");
//
//                } else {
//                    $attendance->leave_types_id_2 = $is_half_day_taken->leave_type_id;
//                }
//            }
//
//            //evening half day check
//            if ($shift_half_day_out_time >= $out_time) {
//                $is_half_day_taken = Leave::where('date', $date)
//                    ->where('employee_id', $employee->id)
//                    ->where('type', 'EVENING_HALF_DAY')
//                    ->first();
//
//                if (is_null($is_half_day_taken)) {
//                    $attendance->status = 'INVALID';
//                    $this->sendMail($date,$employee,'Invalid Attendance');
//
//                } else {
//                    $attendance->leave_types_id_2 = $is_half_day_taken->leave_type_id;
//                }
//
//            }
//
//            if($out_time < $shift_out_time && $out_time > $shift_half_day_out_time){
//                $out_late = $out_time->diffInMinutes($shift_out_time);
//                $this->sendMail($date,$employee,'Early Departure');
//                $attendance->status = 'EARLY_DEPARTURE';
//            }else{
//                $out_late = 0;
//            }
//
//
//            // if leave is taken and still came to work then reverse the leaves even if it is approved
//            $is_leave_taken = Leave::where('date', $date)
//                ->where('employee_id', $employee->id)
//                ->where('type', 'FULL_DAY')
//                ->first();
//
//            if ($is_leave_taken) {
//                $allocated_leaves = LeaveAllocation::where('employee_id', $employee->id)
//                    ->where('leave_type_id', $is_leave_taken->leave_type_id)
//                    ->get();
//
//
//                // Check if any allocation exists
//                if ($allocated_leaves->isNotEmpty()) {
//                    // Update allocated_leaves attribute
//                    $allocated_leavesInstance = $allocated_leaves->first();
//                    $allocated_leavesInstance->used_leaves -= 1;
//                    $allocated_leavesInstance->save();
//                }
//
//                // Cancel the leave
//                $is_leave_taken->status = 'CANCELLED';
//                $is_leave_taken->save();
//            }
//
//
//
//
//
////EARLY_DEPARTURE , LATE
//// Calculate overtime and rounded overtime
//
//
//            if ($employee->eligibility_for_ot == 1) {
//
//                $roundingStrategy = env('OVERTIME_ROUNDING_STRATEGY', 'up');
//                $minimumOvertimeHours = env('OVERTIME_GRACE_HOURS', 0) * 60; //need to discuss with consultant
//                $overtimeDoubleHours = env('OVERTIME_DOUBLE_RATE_HOURS', 0) * 60;
//
////                            dd($minimumOvertimeHours,$overtimeDoubleHours);
//
//                //calculate overtime hours
////                $in_not_not_r = $shift_in_time->greaterThan($in_time) ? max(0, $in_time->diffInMinutes($shift_in_time)) : 0;
//
//                if($shift_in_time>$in_time){
//                    $in_not_not_r = max(0, $in_time->diffInMinutes($shift_in_time));
//                }else{
//                    $in_not_not_r = 0;
//                }
//
////                $out_not_not_r = $out_time->greaterThan($shift_out_time) ? max(0, $out_time->diffInMinutes($shift_out_time)) : 0;
//
//                if($out_time >$shift_out_time){
//                    $out_not_not_r = max(0, $out_time->diffInMinutes($shift_out_time))-(int)$shift_details->late_after;
//                }else{
//                    $out_not_not_r = 0;
//                }
////                            if($employee->id == 17) {
////                                dd($in_not_not_r,$out_not_not_r);
////                            }
//                if ($shift_details->is_in_ot_calculate == 'true') {
//                    if (($in_not_not_r) > $minimumOvertimeHours) {
//                        if ($overtimeDoubleHours != 0 && $in_not_not_r > $overtimeDoubleHours) {
//                            $in_dot_not_r = $in_not_not_r - $overtimeDoubleHours;
////                             $in_dot_temp = $roundingStrategy === 'up' ? ceil($in_dot_not_r) : floor($in_dot_not_r);
//                            $in_dot_temp = $in_dot_not_r;
//                            $in_not = $overtimeDoubleHours;
//                        } else {
////                             $in_not_temp = $roundingStrategy === 'up' ? ceil($in_not_not_r) : floor($in_not_not_r);
//                            $in_dot_temp = 0;
//                            $in_not = $in_not_not_r;
//                        }
//                    } else {
//                        $in_not = 0;
//                        $in_dot_temp = 0;
//                    }
//
//                } else {
//                    $in_not = 0;
//                    $in_dot_temp = 0;
//                }
//
//                if ($shift_details->is_out_ot_calculate == 'true') {
//                    if (($out_not_not_r) > $minimumOvertimeHours) {
//                        if ($overtimeDoubleHours != 0 && $out_not_not_r > $overtimeDoubleHours) {
//                            $out_dot_not_r = $out_not_not_r - $overtimeDoubleHours;
////                             $out_dot_temp = $roundingStrategy === 'up' ? ceil($out_dot_not_r) : floor($out_dot_not_r);
//                            $out_dot_temp = $out_dot_not_r;
//                            $out_not = $overtimeDoubleHours;
//                        } else {
////                             $out_not_temp = $roundingStrategy === 'up' ? ceil($out_not_not_r) : floor($out_not_not_r);
//                            $out_dot_temp = 0;
//                            $out_not = $out_not_not_r;
//                        }
//                    } else {
//                        $out_not = 0;
//                        $out_dot_temp = 0;
//                    }
//
//                } else {
//                    $out_not = 0;
//                    $out_dot_temp = 0;
//                }
//
//
//                $dot = $in_dot_temp + $out_dot_temp;
////                            $dot = 0;
//
//
//                // Calculate total rounded overtime and late minutes
//                $late_min = $in_late + $out_late;
//                $not = $in_not + $out_not;
//                // Update attendance object attributes
//                $attendance->in_not = $in_not;
//                $attendance->out_not = $out_not;
//                $attendance->not = $not;
//                $attendance->dot = $dot;
////::todo
//            } else {
//                $late_min = null;
//                $attendance->in_not = null;
//                $attendance->out_not = null;
//                $attendance->not = null;
//                $attendance->dot = null;
//            }
//
//            $attendance->in_late = $in_late;
//            $attendance->out_late = $out_late;
//            $attendance->late_min = $late_min;
//        }
//    }

    public function sendMail($date,$employee,$message)
    {
        try {
            if($employee->email_address){
                SendBulkEmail::dispatch($employee->email_address, $date,$message);
                self::Log('Bulk Mail Sent', 'Pass');
            }
        }catch(Exception $e) {
            self::Log($e->getMessage(), 'Fail');

        }

    }


}
