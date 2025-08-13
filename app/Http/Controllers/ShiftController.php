<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\HolidayType;
use App\Models\ShiftDetail;
use App\Models\ShiftType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Shift Administration');

        $data['employees'] = Employee::all();
        $data['shiftTypes'] = ShiftType::all();
        $data['shifts'] = ShiftDetail::all();
        $data['holidayTypes'] = HolidayType::all();
        return view('pages.shifts.add-shifts')->with($data);
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

    public function AddShiftTypes(Request $request)
    {
        try {
            DB::beginTransaction();
            $ShiftType = new ShiftType();
            $formData = $request->validate(['shift_name'=>'required','shift_code'=>'required']);
            $ShiftType->create($formData);
            self::Log('Shift Type Added', 'Pass');

            DB::commit();
            return back()->with('message','Shift Type Added Successfully !');
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollback();
            return back()->with('error', 'Error Adding Shift Types');

        }
    }


    public function AddShiftDetails(ShiftRequest $request)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validated();
//            dd($formData);
        $shift_detail=ShiftDetail::create($formData);
            if($request->out_time != null){
                $shift_detail->day_expected = 'true';
                $shift_detail->save();
            }
            $inTime = Carbon::createFromFormat('H:i', $request->in_time);
            $outTime = Carbon::createFromFormat('H:i', $request->out_time);

            if ($inTime->gt($outTime)) {
                $shift_detail->midnight_crossover = true;
                $shift_detail->save();
            }


            self::Log('Shift Added', 'Pass');

            DB::commit();
            return back()->with('message','Shift Added SuccessFully');
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
         return back()->with('error','Error Adding Shifts !');
    }

    }

    public function updateShifts(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                EmployeeShift::updateOrCreate(
                    ['employee_id' => $item['value']],
                    ['employee_id' => $item['value'], 'shift_id' => $request->shift_id]
                );
            }
            DB::commit();
            self::Log('Shift Updated', 'Pass');

            return response()->json(['message' => 'Updates Successfully !']);
        } catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
            return response()->json(['error' => 'Failed']);
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
