<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LeaveController;
use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\AttendanceTemp;
use App\Models\Day;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\Leave;
use App\Models\ShiftDetail;
use App\Models\ShiftType;
use App\Models\ShortLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AttendanceController as AttendanceCtrl;

class AttendanceController extends Controller
{


//    public function storeData($request)
//    {
//        $dataArray = $request->data_array;
//        $date = $request->date;
//
//        $i = count($dataArray);
////        dd($i);
//        $employeeData = [];
//        for ($j = 1; $j < $i; $j++){
//            $attendanceTemp = new AttendanceTemp();
//            $fingerprint_id = $dataArray[$j][1];
//            if($fingerprint_id===null){
//                continue;
//            }
//            $employee = Employee::where('fingerprint_id','=',$fingerprint_id)->where('status','ACTIVE')->first();
////            dd($employee->fingerprint_id);
//            if (!$employee) {
//                continue;
//            }
//            $employee_shift = EmployeeShift::where('employee_id','=',$employee->id)->first();
////           dd($shift_id);
//
//            if (!$employee_shift) {
//                continue;
//            }
//
//            $shift = ShiftType::find($employee_shift->shift_id);
//
////            dd($shift);
//
////            $fingerprint_id = $dataArray[$j][1];
//            $timestamp = $dataArray[$j][0];
//            $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('Y-m-d');
//            $Time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('H:i');
//
//            $attendanceTemp->employee_id = $employee->id;
//            $attendanceTemp->shift_id = $shift->id;
//            $attendanceTemp->fingerprint_id = $employee->fingerprint_id;
//            $attendanceTemp->date = $date;
//            $attendanceTemp->time = $Time;
//
//            $attendanceTemp->save();
//
//        }
//    }




    public function storeData(Request $request)
    {
        try {
            Log::info('Attendance data received:', $request->all());

            // Decode the JSON data array from the request
            $dataArray = $request->input('data_array'); // Already an array, no need to decode
            $dateString = $request->input('date');

            if (!$dataArray || !is_array($dataArray)) {
                return response()->json(['error' => 'Invalid data format'], 400);
            }

            // Start a DB transaction
            DB::beginTransaction();

            foreach ($dataArray as $data) {
                // Ensure that each item in the array has the necessary fields
                if (!isset($data['Timestamp']) || !isset($data['FingerprintId'])) {
                    continue;
                }

                $fingerprintId = $data['FingerprintId'];
                $timestamp = $data['Timestamp'];

                $employee = Employee::where('fingerprint_id', $fingerprintId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if (!$employee) {
                    continue;
                }

                $employeeShift = EmployeeShift::where('employee_id', $employee->id)->first();
                if (!$employeeShift) {
                    continue;
                }

                $shift = ShiftType::find($employeeShift->shift_id);
                if (!$shift) {
                    continue;
                }

                // Parse the timestamp into date and time
                $parsedTimestamp = Carbon::createFromFormat('m/d/Y h:i:s A', $timestamp);
                $date = $parsedTimestamp->toDateString();
                $time = $parsedTimestamp->format('H:i:s');

                // Create new AttendanceTemp record
                $attendanceTemp = new AttendanceTemp();
                $attendanceTemp->employee_id = $employee->id;
                $attendanceTemp->shift_id = $shift->id;
                $attendanceTemp->fingerprint_id = $employee->fingerprint_id;
                $attendanceTemp->date = $date;
                $attendanceTemp->time = $time;

                $attendanceTemp->save();
            }

            // Convert the date to a consistent format for comparison
            $dateConverted = Carbon::createFromFormat('m/d/Y', $dateString)->toDateString();
            $yesterday = Carbon::yesterday()->toDateString();

            if ($dateConverted <= $yesterday) {
                $this->handleAttendance($dateConverted);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Error storing data'], 400);
            }

            // Commit the transaction if all operations succeed
            DB::commit();

            return response()->json(['success' => 'Data stored successfully'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the exception message for debugging purposes
            Log::error('Error storing attendance data:', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'An error occurred while storing the data. Please try again.'], 500);
        }
    }



    public function handleAttendance($date)
    {
          // if(isset($request->attendance_sheet)) {

            $attendanceCtrl = new AttendanceCtrl();

            try {
             // $date = $request->attendance_date;

             // Excel::import( new AttendanceImport($date), $request->file('attendance_sheet'));

                $employees = Employee::where('status','ACTIVE')->get();;
                DB::beginTransaction();
                $message =1;
                foreach ($employees as $employee){

                    $existingAttendance = Attendance::where('date', $date)
                        ->where('employee_id', $employee->id)
                        ->first();

                    // If the record exists, skip to the next iteration
                    if ($existingAttendance) {
                        continue;
                    }


                    // $attendanceTemp =  AttendanceTemp::where('date',$date)->get();
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
                            $attendanceCtrl->sendMail($date,$employee,"Invalid Attendance");
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
                        $attendanceCtrl->inTimeOutTimeRecordedValidation($min_time, $max_time, $shift_details, $date, $attendance, $employee,$message);

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
//                self::Log('Attendance Updated Successfully', 'Pass');
//                return back()->with('message','Attendance Updated Successfully');

            }catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error storing attendance data:', ['error' => $e->getMessage()]);

            }

//        }




    }





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
}
