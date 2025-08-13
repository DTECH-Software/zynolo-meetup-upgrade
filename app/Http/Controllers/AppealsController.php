<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Attendance;
use App\Models\AttendanceAppeals;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppealsController extends Controller
{
    use PermissionTrait,AuditLogTrait;

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
        try {
            DB::beginTransaction();
            $form_data = $request->validate(['in_time'=>'nullable','out_time'=>'nullable','reason'=>'nullable']);
            $attendance_data = Attendance::where('id',$request->attendance_id)->first();
            AttendanceAppeals::create($form_data);
            self::Log('Stored Successfully', 'Pass');

            DB::commit();

        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();
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
}
