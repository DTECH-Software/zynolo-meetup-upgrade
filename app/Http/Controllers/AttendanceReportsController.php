<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        if (Auth::user()->hasRole('Super Admin') || Auth::user()->can('View All Attendance Data')) {
            $data['attendance_data'] = Attendance::with('employees')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
        } elseif(Auth::user()->hasRole(['HOD'])){

            $employees = Employee::with('departments')
                ->where('status', '=', 'ACTIVE')
                ->where(function($query) {
                    $query->where('reporting_person_id', Auth::user()->id)
                        ->orWhere('id', Auth::user()->employee_id);
                })
                ->orderByRaw("id = ? DESC", [Auth::user()->employee_id])
                ->orderBy('id')
                ->get();

            $employee_ids = $employees->pluck('id');

            $data['attendance_data'] = Attendance::with('employees')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->whereIn('employee_id', $employee_ids)
                ->get();
        }else {
            $data['attendance_data'] = Attendance::where('employee_id', Auth::user()->employee_id)
                ->with('employees')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
        }
        return view('pages.attendance_reports.view-time-sheet')->with($data);
    }


    public function FilterAttendance(Request $request)
    {
        $startOfMonth = $request->start_date;
        $endOfMonth = $request->end_date;

        if (Auth::user()->hasRole('Super Admin') || Auth::user()->can('View All Attendance Data')) {
            $data['attendance_data'] = Attendance::with('employees')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
        } else {
            $data['attendance_data'] = Attendance::where('employee_id', Auth::user()->employee_id)
                ->with('employees')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
        }
        return view('pages.attendance_reports.view-time-sheet')->with($data);

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
