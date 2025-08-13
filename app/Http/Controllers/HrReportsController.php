<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HrReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.reports.generate-reports');
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
        ini_set('max_execution_time', 300);

//        Attendance::whereMonth('date',$request->month)->whereYear('year',$request->year)->get();
        $request->validate([
            'month' => 'required|between:1,12',
            'year' => 'required|digits:4'
        ]);

        $month=(int)$request->month;
        $year=$request->year;

        $employees = Employee::all();



//        dd($attendance_data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['EPF No', 'Employee Name', 'Department', 'Working Days', 'Leaves', 'Late'];
        for ($day = 1; $day <= 31; $day++) {
            $headers[] = $day;
        }
        $sheet->fromArray($headers, null, 'A1');

        // Define colors
        $colors = [
            'visited_office' => 'FF00FF00', // Green
            'half_day' => '80FF0000', // transparent red
            'leave' => 'FFFF0000', // Red
            'holiday_weekend' => 'FF808080', // Gray
            'invalid' => 'FF800000' // Maroon
        ];

        $user = Auth::user();

        if($user->hasRole(['HOD'])){
//                reporting_person_id
            $employees = Employee::with('departments')->where('status', '=','ACTIVE')->where('reporting_person_id', $user->id)->Orwhere('id', $user->employee_id)->get();
        }elseif($user->hasRole(['User'])){
            $employees = Employee::with('departments')->where('status', '=','ACTIVE')->where('id', $user->employee_id)->get();

        }else{
            $employees =Employee::with('departments')->where('status', '=','ACTIVE')->get();
        }

//        dd$day);
        // Populate the sheet with attendance data
        $row = 2;
        foreach ($employees  as $employee){



        $attendance_data = Attendance::with('employees')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('employee_id', '=',$employee->id)
            ->get();



            $working_day_count = $attendance_data->filter(function ($attendance) {
                return is_null($attendance->leave_types_id_1) &&
                    is_null($attendance->leave_types_id_2) &&
                    ($attendance->status == 'VALID' || $attendance->status == 'LATE' || $attendance->status == 'EARLY_DEPARTURE');
            })->count();


            $full_day_count = $attendance_data->whereNotNull('leave_types_id_1')->count();
            $half_day_count = $attendance_data->whereNotNull('leave_types_id_2')->count();
            $half_day_count_int = (int)$half_day_count;
            $total_leaves = (int)$full_day_count + ($half_day_count_int / 2);

            $late_count = $attendance_data->filter(function ($attendance) {
                return $attendance->status == 'LATE' || $attendance->status == 'EARLY_DEPARTURE';
            })->count();

        foreach ($attendance_data as $attendance) {

            //            $employee = $attendance->employee;
            if($employee->epf_etf_number != null){
                $sheet->setCellValue("A{$row}", $employee->epf_etf_number);
            }else{
                $sheet->setCellValue("A{$row}", $employee->employee_number);
            }
            $sheet->setCellValue("B{$row}", $employee->first_name. ' ' .$employee->last_name );
            $sheet->setCellValue("C{$row}",$employee->departments->name );
            $sheet->setCellValue("D{$row}", $working_day_count);
            $sheet->setCellValue("E{$row}", $total_leaves);
            $sheet->setCellValue("F{$row}", $late_count);

            for ($day = 1; $day <= 31; $day++) {
                $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
//                $attendanceDay = $attendance->where('date', $date)->first();

                $cell = $sheet->getCellByColumnAndRow($day + 6, $row);
//                if ($attendance) {
                    if($attendance->date == $date) {

                        if ($attendance->leave_types_id_1) {
                            $cell->setValue('L'); // Leave
                            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colors['leave']);
                        } elseif ($attendance->leave_types_id_2) {
                            $cell->setValue('H'); // Half day
                            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colors['half_day']);
                        }elseif ($attendance->status == 'INVALID'){
                            $cell->setValue('I'); // Half day
                            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colors['invalid']);
                        } else {
                            $cell->setValue('W'); // Working day
                            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colors['visited_office']);
                        }

                    } else {
//                    $cell->setValue('H'); // Holiday/Weekend
//                    $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colors['holiday_weekend']);
                }
//                }
//
            }



        }

            $row++;

        }


        $fileName = 'attendance_report.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Save to the temporary file
        $writer = new Xlsx($spreadsheet);
        $writer->save($temp_file);

        // Stream the file to the browser
        return response()->streamDownload(function() use ($temp_file) {
            readfile($temp_file);
            unlink($temp_file); // Delete the temporary file
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

        // Return the file as a response
//        return response()->download($filePath)->deleteFileAfterSend(true);

//        dd($attendance_data);
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
