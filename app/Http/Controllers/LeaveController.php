<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Attendance;
use App\Models\Day;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\Hierarchy;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\LeaveDetail;
use App\Models\LeaveReason;
use App\Models\LeaveType;
use App\Models\ShiftDetail;
use App\Models\ShiftType;

use App\Models\ShortLeave;
use App\Models\ShortLeaveType;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Faker\Provider\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LeaveController extends Controller
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
        self::HandlePermission('Add Leave Details');

        $data['leave_types']=LeaveType::all();
        return view('pages.leaves.add-leave-types')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addLeaveTypes(Request $request)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validate(['name'=>'required','is_unlimited'=>'nullable','is_paid'=>'nullable','amount'=>'required']);
            LeaveType::create($formData);
            DB::commit();
            self::Log('Leave Type Added', 'Pass');
            return back()->with('message','Leave Type Added Successfully !');
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();
            return back()->with('error','Error Adding Leave Type !');
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $formData = $request->validate(['id'=>'required','status'=> 'required']);
            $leave_type = LeaveType::find($request->id);
//            $leave_type->update($formData);
            $leave_type->status = $request->status;
            self::Log('Status Changed', 'Pass');
            $leave_type->save();
            return response()->json($leave_type);
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            return response()->json(['error'=>$e->getMessage()]);
        }

    }

    public function leaveForm()
    {
        self::HandlePermission('Apply Leaves');

//        dd( Session::all());

       $employee_id = Auth::user()->employee_id;

//       dd( Auth::user());

       $employee_data =  Employee::where('id', $employee_id)->first();


       if(Auth::user()->hasRole('Super Admin')){
           $data['company_employees'] = Employee::where('company_id', $employee_data->company_id)->get();

       }else{
           $data['company_employees'] =  Employee::where('id', $employee_id)->get();

       }
//dd($data['company_employees']);

        $data['leave_types'] = LeaveType::all();
        $data['leave_reasons'] = LeaveReason::all();
        return view('pages.leaves.apply-leaves')->with($data);

    }

    public function EmployeeLeaveData($id)
    {
        $employee_data = Employee::with('current_designations','reporting_person')->find($id);

        return response()->json($employee_data);

    }

    public function filterDays(request $request)
    {
        $LeaveTo = $request->leave_to;
        $LeaveFrom = $request->leave_from;
        $employee_id = $request->employee_id;
        $employeeDetails = Employee::find($employee_id);
        $dates = $this->getDaysInDateRange($LeaveFrom,$LeaveTo);
        $shift_details = EmployeeShift::where('employee_id',$employee_id)->first();
        $WorkingDays = $this->filterWorkingDays($dates,$employeeDetails->company_id,$shift_details->id);
        return response()->json($WorkingDays);
    }

    public function filterWorkingDays($dates,$company_id,$shift_type_id)
    {
        $calanderDates = Day::where('day_type','HOLIDAY')->where('status','ACTIVE')->where('company_id',$company_id)->get();
        $shiftDetails = ShiftDetail::where('shift_type_id',$shift_type_id)->get();
//        dd($shiftDetails);


        $shiftHolidays = [];
        foreach ($shiftDetails as $shiftDetail) {
            if ($shiftDetail->day_expected == 'true') {
                $holidays = Day::where('holiday_type_id', $shiftDetail->day_type_id)->get();
                foreach ($holidays as $holiday) {
                    $shiftHolidays[] = $holiday->date;
                }
            }
        }


        $WorkingDays = [];

        foreach ($dates as $date) {
            $isHoliday = false;
            // Check if the date is a shift holiday
            // Check if the date is in the calendar
            foreach ($calanderDates as $calanderDate) {
                if ($date == $calanderDate->date) {
                    $isHoliday = true;
                    break;
                }
            }

            foreach ($shiftHolidays as $holiday) {
//                foreach ($holiday as $shiftHoliday) {
                if ($date == $holiday) {
                    $isHoliday = false;
                    break; // break out of both inner and outer loops
                }
//                }
            }

            // If it's not a holiday, add it to the working days
            if (!$isHoliday) {
                $WorkingDays[] = $date;
            }


        }
        $WorkingDaysFinal = [];
        $index = 0;

        foreach ($WorkingDays as $WorkingDay) {
            $day = Day::where('date',$WorkingDay)->first();
            $shiftDetails = ShiftDetail::where('day_type_id',$day->holiday_type_id)->first();
//            dd($shiftDetails->out_time,$shiftDetails->in_time);
            $startTime = Carbon::parse($shiftDetails->in_time);
            $endTime = Carbon::parse($shiftDetails->out_time);
            $hourDifference = $endTime->diffInHours($startTime);
//            $WorkingHours = ($shiftDetails->out_time) - ($shiftDetails->in_time);
            if ($hourDifference<= $shiftDetails->half_day_length){
                $dayType = 'MORNING_HALF_DAY';
            }else{
                $dayType = 'FULL_DAY';
            }
            $WorkingDaysFinal[$index][0] = $WorkingDay;
            $WorkingDaysFinal[$index][1] = $dayType;
            $index++;
        }
//        dd($WorkingDaysFinal);
// TODO:use day type id and shift id  to get shift of the current day type , subtract out time from in time and compare it with half day hours if they are similar or lesser than half day hours then it is considered a half-day
        return $WorkingDaysFinal;
    }



    public function getDaysInDateRange($LeaveFrom,$LeaveTo)
    {

        $start = Carbon::parse($LeaveFrom);
        $end = Carbon::parse($LeaveTo);

        $period = CarbonPeriod::create($start,$end);

        $dates = [];

        foreach($period as $date){
            $dates[] = $date->toDateString();
        }

        return $dates;

    }


    public function getRemainingLeaves(Request $request)
    {
        $leave_allocation = LeaveAllocation::where('employee_id',$request->employee_id)->where('leave_type_id',$request->type_id)->first();

        return response()->json($leave_allocation);
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

        //employee id
        //dates
        //check if dates are in the table  dates column  with employee id

//        dd($request);
        try {
            DB::beginTransaction();
            $count = 0;
            foreach ($request->rows as $row) {
                if ($row['session_type'] == 'FULL_DAY') {
                    $count++;
                } elseif($row['session_type'] == 'MORNING_HALF_DAY' || $row['session_type'] == 'EVENING_HALF_DAY') {
                    $count += 0.5;
                }
            }

            $leave_details = new LeaveDetail();
            $leave_details->employee_id = $request->id_employee;
            $leave_details->leave_type_id = $request->leave_type;
            $leave_details->leave_from = $request->leave_from;
            $leave_details->leave_to = $request->leave_to;
            $leave_details->amount = $count;
            $leave_details->approver_id = $request->approver_id;
            $leave_details->leave_reason_id = $request->leave_reason_id;
            $leave_details->save();

            foreach ($request->rows as $row){
                $leaves = new Leave();
                $leaves->leave_details_id = $leave_details->id;
                $leaves->date = $row['dateInput'];
                $leaves->type = $row['session_type'];
                $leaves->employee_id = $request->id_employee;

                $today = Carbon::now();
                $leave_date = Carbon::parse($row['dateInput']);
                if($leave_date < $today){
                    $leaves->approval_status ='POST_APPROVED';
                }
                if ($row['session_type'] == 'FULL_DAY'){
                    $leaves->amount = 1;
                }else {
                    $leaves->amount = 0.5;
                }
                $leaves->leave_type_id = $request->leave_type;
//                dd($row['dateInput']);
                $leaves->year = Carbon::createFromFormat('Y-m-d', $row['dateInput'])->format('Y');
                $leaves->save();


            }

            $employee= Employee::find($request->id_employee);
            $reciver= User::find($employee->reporting_person_id);;
            $message = "{$employee->known_name} has submitted a leave request from {$request->leave_from} to {$request->leave_to}. Click below to review.";

          $mail=  Mail::to($reciver->email)->send(new \App\Mail\hr_mail($message));
            DB::commit();
            self::Log('Leave Requested', 'Pass');
            return back()->with('message','Leave Requested Successfully !');
        }catch (\Exception $e) {
          DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return back()->with('error','Error Requesting Leaves !');
        }


        //after approval
//        dd($request);
//        $employee_allocated_leaves = LeaveAllocation::where('employee_id',$request->employee_id)->get();
//        foreach ($employee_allocated_leaves as $leave){
//         foreach ($request->$rows as $row){
//             if ($row->leaveType == $leave->leave_type_id) {
//                 $leave->allocated_leaves -- ;
//             }
//         }
//        }
    }

    /**
     * Display the specified resource.
     */
    public function addLeaveReasons(Request $request)
    {
        try {
            DB::beginTransaction();
            $formData=$request->validate(['name'=>'required']);

            LeaveReason::create($formData);
            self::Log('Leave Reason Added', 'Pass');
            DB::commit();
            return back()->with('message','Leave Reason Added Successfully !');
        }catch (\Exception $e){
             DB::rollback();
            self::Log($e->getMessage(), 'Fail');
            return back()->with('error','Error Adding Leaves !');
    }


    }


    public function reviewLeavesView()
    {
        self::HandlePermission('Review Leaves');

        $current_user_id = Auth::user()->id;
        $data['requested_leaves'] = LeaveDetail::where('approver_id',$current_user_id)->where('status','NOT_REVIEWED')->with('leaves','employee','leavetype')->get();
        $data['all_approved__leaves'] = LeaveDetail::where('approver_id',$current_user_id)->where('status','REVIEWED')->whereHas('leaves',function ($query){
           $query->where('status','APPROVED');
        })->with('leaves','employee','leavetype')->get();
     return view('pages.leaves.review-leaves')->with($data);
    }

    public function getLeaves(Request $request)
    {
            $leaves = Leave::where('leave_details_id', $request->id)->get();
           return response()->json($leaves);
    }

    public function Reviewleaves(Request $request)
    {
//        dd($request->date_array[0]['employee_id']);
        try {
            DB::beginTransaction();
            $leave_details_id=$request->date_array[0]['leave_details_id'];
            $leave_details = LeaveDetail::find($leave_details_id);
            $leave_details->status = 'REVIEWED';
            $leave_details->save();
//        dd($leave_details_id);
//            dd($request->date_array);
            $leave_message = '';
            foreach ($request->date_array as $leave){
                $current_leave = Leave::where('date',$leave['date'])->where('employee_id',$request->date_array[0]['employee_id'])->first();
                $remaining_leaves = LeaveAllocation::where('employee_id',$request->date_array[0]['employee_id'])->where('leave_type_id',$request->date_array[0]['leave_type_id'])->first();
//                dd($current_leave);
                $leave_message .= "Date: {$leave['date']} - {$leave['remark']}\n,";
                if ($leave['remark']== 'approved') {

                        $current_leave->status = 'APPROVED';
//                        $remaining_leaves->allocated_leaves = (float)$remaining_leaves->allocated_leaves - (float)$leave['amount'];
                        $remaining_leaves->used_leaves += (float)$leave['amount'];

                    if($current_leave->approval_status == 'POST_APPROVED'){
                        $attendance_data = Attendance::where('date',$leave['date'])->where('employee_id',$request->date_array[0]['employee_id'])->first();
                        if($attendance_data){
                            $attendance_data->status = 'VALID';
                            if($current_leave->type == 'FULL_DAY'){
                                $attendance_data->leave_types_id_1 = $request->date_array[0]['leave_type_id'];
                            }else{
                                $attendance_data->leave_types_id_2 = $request->date_array[0]['leave_type_id'];
                            }
                            $attendance_data->save();
                        }
                    }

                }else if($leave['remark']== 'rejected'){
                    $current_leave->status = 'REJECTED';
                    $current_leave->review = $leave['review'];
                }
                $current_leave->save();
                $remaining_leaves->save();
            }




            DB::commit();
            $employee= Employee::find($leave_details->employee_id);
            $message = "{$employee->known_name}, your leave request status are \n{$leave_message}";
            if($employee->email_address){
                Mail::to($employee->email_address)->send(new \App\Mail\hr_mail($message));
            }

            self::Log('Leave Details updated', 'Fail');

            return back()->with('message', 'Leave Details updated Successfully !');

        }catch (\Exception $e){
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
            return back()->with('error','Error Updating Leave Details !');

    }


    }

    public function ApplyShortLeaves()
    {
        self::HandlePermission('Apply Short Leaves');

        $employee_id = Auth::user()->employee_id;

        $employee_data =  Employee::where('id', $employee_id)->first();

        $data['short_leave_reasons'] = ShortLeaveType::all();

        if(Auth::user()->name == 'Super Admin'){
            $data['company_employees'] = Employee::where('company_id', $employee_data->company_id)->get();

        }else{
            $data['company_employees'] = Employee::where('id', $employee_id)->get();

        }

        return view('pages.leaves.apply-short-leaves')->with($data);
    }

    public function storeShortLeaves(Request $request)
    {
        self::HandlePermission('Apply Short Leaves');

        try {
            DB::beginTransaction();
        $short_leave = new ShortLeave();
        $short_leave->employee_id = $request->id_employee;
        $short_leave->date = $request->date;

//        throw new \Exception("");
            $employee = Employee::find($short_leave->employee_id);
            $company = Hierarchy::find($employee->company_id);
            $month = (int)Carbon::createFromFormat('Y-m-d',$short_leave->date)->format('m');
            $short_leaves = ShortLeave::whereRaw('MONTH(date) = ?',$month)->where('employee_id',$employee->id)->where('status','APPROVED')->get();
            if(count($short_leaves)>0){
                if (count($short_leaves)>=(int)$company->monthly_short_leave_attempts){
                    return back()->with('error','You have exceeded the monthly short leave limit !');
                }
            }

        $short_leave->short_leave_type = $request->short_leave_type;
        $short_leave->approver_id=$request->approver_id;
        $short_leave->short_leave_type_id=$request->short_leave_type_id;

        $day = Day::where('date', $request->date)->first();
        $shift_type = EmployeeShift::where('employee_id', $request->id_employee)->first();
//        dd($shift_type);

            $shift_detail= ShiftDetail::where('shift_type_id', $shift_type->shift_id)->where('day_type_id',$day->holiday_type_id)->first();
//            dd($company);
        $short_leave->hours_amount = $company->monthly_short_leave_allowance;

        $short_leave->save();
            $reciver =User::find($employee->reporting_person_id);
            $message = "Short leave request has been submitted by {$employee->known_name} for the date {$short_leave->date}. Click the button below to review and take action.";
            Mail::to($reciver->email)->send(new \App\Mail\hr_mail($message));

            DB::commit();
            self::Log('Leave Requested', 'Fail');

            return back()->with('message','Leave Requested Successfully !');
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
        return back()->with('error','Error Appplying Short Leaves !');
        }



    }

    public function reviewShortLeaves()
    {
        self::HandlePermission('Review Short Leaves');

        $current_user_id = Auth::user()->id;

        $data['requested_short_leaves'] = ShortLeave::with('employee')->where('approver_id',$current_user_id)->where('status','REQUESTED')->get();
        return view('pages.leaves.review-short-leaves')->with($data);
    }

    public function reviewShortLeavesStatus(Request $request)
    {
        self::HandlePermission('Review Short Leaves');

        try {
                DB::beginTransaction();
                $shortLeave = ShortLeave::find($request->leave_id);
                $employee= Employee::find($shortLeave->employee_id);

            $attendance = Attendance::where('date',$shortLeave->date)->where('employee_id',$shortLeave->employee_id)->first();
            if($request->status=='APPROVED' && isset($attendance)) {
                $this->process_shortleaves($shortLeave, $attendance);
                $attendance->save();

            }

            if($request->status=='APPROVED') {
                $message = "Your short leave request for {$shortLeave->date} has been approved.";
                if($employee->email_address){
                    Mail::to($employee->email_address)->send(new \App\Mail\hr_mail($message));
                }
            }
            if($request->status=='REJECTED'){
                $message = "Your short leave request for {$shortLeave->date} has been rejected.";
                if($employee->email_address){
                    Mail::to($employee->email_address)->send(new \App\Mail\hr_mail($message));
                }
            }
                $shortLeave->status = $request->status;
                $shortLeave->review = $request->review;
                $shortLeave->save();
                DB::commit();
            self::Log('Leave Reviewed', 'Pass');

            return back()->with('message', 'Leave Reviewed !');
            }catch(Exception $e){
            DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return  back()->with('error','ERROR !');
            }

    }

    public function search_emp(Request $request)
    {
        if($request->ajax()) {
//            dd($request);

            $output = "";
            $employees = DB::table('employees')->where('first_name', 'LIKE', '%' . $request->search . "%")->get();
//            dd($employees);
//            if ($employees) {
//                foreach ($employees as $key => $employee) {
////                    $output .= '<option value="'.$employee->id.'">' . $employee->first_name . '</option>' ;
//
//                }
//                return Response($output);
//            }

            return response()->json($employees);

        }
    }




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


    public function get_leave_days(Request $request)
    {
        $employee_id = $request->employee_id;
        $type_id = $request->type_id;

        $year = Carbon::now()->format('Y');

        $leaves = LeaveDetail::where('employee_id', $employee_id)
            ->whereHas('leaves', function ($query) use ($year,$type_id) {
                $query->where('year', $year)->where('status', 'APPROVED')->where('leave_type_id',$type_id);
            })
            ->with('leaves')
            ->get();

        return response()->json($leaves);


    }

    public function AddShortLeaveTypes(Request $request)
    {
        try {
            $formData= $request->validate(['type'=>'required']);
            ShortLeaveType::create($formData);
            self::Log('Short leave reason added', 'Fail');

            return back()->with('message','Short Leave Reason Added Successfully !');
        }catch (\Exception $e){
            self::Log($e->getMessage(), 'Fail');
            return back()->with('error','Error while creating Short Leave Reasons !' );
        }

    }

    /**
     * @param $shortLeave
     * @param $attendance
     * @return void
     */
    public function process_shortleaves($shortLeave, $attendance): void
    {
        $employee = Employee::find($shortLeave->employee_id);

        $company = Hierarchy::find($employee->company_id);
        if ($shortLeave->short_leave_type == 'MORNING_SHORT_LEAVE') {
            if ((int)$attendance->in_late > (int)$company->monthly_short_leave_allowance) {
                $attendance->in_late = (int)$attendance->in_late - (int)$company->monthly_short_leave_allowance;
                $attendance->late_min = (int)$attendance->late_min - (int)$company->monthly_short_leave_allowance;
                $attendance->status = 'LATE';

            } else {
                $attendance->in_late = 0;
                if ((int)$attendance->late_min > (int)$company->monthly_short_leave_allowance) {
                    $attendance->late_min = (int)$attendance->late_min - (int)$company->monthly_short_leave_allowance;
                    $attendance->status = 'LATE';
                } else {
                    $attendance->late_min = 0;
                    $attendance->status = 'VALID';
                }
            }
        } elseif ($shortLeave->short_leave_type == 'EVENING_SHORT_LEAVE') {
            if ((int)$attendance->out_late > (int)$company->monthly_short_leave_allowance) {
                $attendance->out_late = (int)$attendance->out_late - (int)$company->monthly_short_leave_allowance;
                $attendance->late_min = (int)$attendance->late_min - (int)$company->monthly_short_leave_allowance;
                $attendance->status = 'LATE';
            } else {
                $attendance->out_late = 0;
                if ((int)$attendance->late_min > (int)$company->monthly_short_leave_allowance) {
                    $attendance->late_min = (int)$attendance->late_min - (int)$company->monthly_short_leave_allowance;
                    $attendance->status = 'LATE';
                } else {
                    $attendance->late_min = 0;
                    $attendance->status = 'VALID';
                }
            }
        }
        if(isset($attendance->short_leave_hrs)){
            $total = (int)$company->monthly_short_leave_allowance + (int)$attendance->short_leave_hrs;

        }
        $attendance->short_leave_hrs = $total ?? $company->monthly_short_leave_allowance;
    }
}
