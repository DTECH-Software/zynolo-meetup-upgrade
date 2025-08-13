<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\AttendanceTemp;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\ShiftDetail;
use App\Models\ShiftType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToCollection
{
    private $date;

    public function __construct($additionalVariable)
    {
        $this->date = $additionalVariable;
    }

    public function collection(Collection $rows)
    {
        $dataArray = $rows->toArray();
         $this->storeData($dataArray,$this->date);

        return $dataArray;
    }

    public function storeData($dataArray,$date)
    {
        $i = count($dataArray);
//        dd($i);
        $employeeData = [];
        for ($j = 1; $j < $i; $j++){
            $attendanceTemp = new AttendanceTemp();
           $fingerprint_id = $dataArray[$j][1];
           if($fingerprint_id===null){
               continue;
           }
            $employee = Employee::where('fingerprint_id','=',$fingerprint_id)->where('status','ACTIVE')->first();
//            dd($employee->fingerprint_id);
            if (!$employee) {
                continue;
            }
            $employee_shift = EmployeeShift::where('employee_id','=',$employee->id)->first();
//           dd($shift_id);

            if (!$employee_shift) {
                continue;
            }

            $shift = ShiftType::find($employee_shift->shift_id);

//            dd($shift);

//            $fingerprint_id = $dataArray[$j][1];
            $timestamp = $dataArray[$j][0];
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('Y-m-d');
            $Time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('H:i');

            $attendanceTemp->employee_id = $employee->id;
            $attendanceTemp->shift_id = $shift->id;
            $attendanceTemp->fingerprint_id = $employee->fingerprint_id;
            $attendanceTemp->date = $date;
            $attendanceTemp->time = $Time;

            $attendanceTemp->save();

        }
    }


}
