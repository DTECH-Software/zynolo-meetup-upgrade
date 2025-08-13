<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Employee;
use App\Models\Hierarchy;
use App\Models\Day;
use App\Models\HolidayType;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Company;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalenderController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        self::HandlePermission('Shift Administration');

        try {
            DB::beginTransaction();
            $today = Carbon::now();
            $hierarchies = Hierarchy::all();

            if ($today->isLastOfMonth() && $today->month == 12) {
//                $year = Carbon::now()->addYear()->format('Y');
                $year = '2024';
                $holidayTypeWeekend = HolidayType::where('type', '=', 'WEEKEND')->first();
                $dayTypeSaturday = HolidayType::where('type', '=', 'SATURDAY')->first();
                $dayTypeSunday = HolidayType::where('type', '=', 'SUNDAY')->first();
                $TypeDay = HolidayType::where('type', '=', 'DAY')->first();
                foreach ($hierarchies as $hierarchy) {
//                    dd($hierarchy->weekend_day_1, $hierarchy->weekend_day_2);
                    $days = [$hierarchy->weekend_day_1, $hierarchy->weekend_day_2];
                    $weekendDays = $this->getHolidays($days);


                    foreach ($weekendDays as $weekendHoliday) {
                        $this->saveHoliday($hierarchy, $year, $holidayTypeWeekend,$TypeDay, $weekendHoliday,$dayTypeSaturday,$dayTypeSunday);
                    }
                }

                $employees = Employee::all();
                $leave_types = LeaveType::all();
                foreach ($employees as $employee){
                    foreach ($leave_types as $leave_type){
                        $leave_allocations = new LeaveAllocation();
                        $leave_allocations->employee_id = $employee->id;
                        $leave_allocations->leave_type_id = $leave_type->id;
                        $leave_allocations->year = $year;
                        $leave_allocations->allocated_leaves = $leave_type->amount;
                        $leave_allocations->used_leaves = 0;
                        $leave_allocations->save();
                    }
                }

            }


            self::Log('Shift Allocated', 'Pass');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            self::Log($e->getMessage(), 'Fail');
        }

        $employee_id = Auth::user()->employee_id;
        $employee_data = Employee::find($employee_id);
        $company_id = $employee_data->company_id;

        if(Auth::user()->hasRole('Super Admin')) {

            $data['hierarchies'] = $hierarchies;

        }else{
            $data['hierarchies'] = Hierarchy::where('id',$company_id)->get();
        }

        $data['holiday_types'] = HolidayType::all();

        return view('pages.shifts.add-calender')->with($data);
    }




    public function getHolidays($weekendDays)
    {
//        $year = Carbon::now()->addYear()->format('Y');
        $year = '2024';

        $startOfYear = Carbon::create($year, 1, 1);

        $holidayDates = [];

        // Iterate over each day of the year
        for ($day = $startOfYear->copy(); $day->year == $year; $day->addDay()) {
            $dayOfWeek = $day->englishDayOfWeek;
            if (in_array($dayOfWeek, $weekendDays)) {
                $holidayDates[] = [
                    'date' => $day->copy(),
                    'day_type' => 'HOLIDAY',
                ];
            } elseif ($dayOfWeek === 'Saturday' && !in_array('Saturday', $weekendDays)) {
                $holidayDates[] = [
                    'date' => $day->copy(),
                    'day_type' => 'SATURDAY',
                ];
            } elseif ($dayOfWeek === 'Sunday' && !in_array('Sunday', $weekendDays)) {
                $holidayDates[] = [
                    'date' => $day->copy(),
                    'day_type' => 'SUNDAY',
                ];
            } else {
                $holidayDates[] = [
                    'date' => $day->copy(),
                    'day_type' => 'DAY',
                ];
            }
        }


        return $holidayDates;
    }

    public function saveHoliday($hierarchy, $year, $holidayTypeWeekend,$holidayTypeDay, $holidayData,$dayTypeSaturday,$dayTypeSunday)
    {
//        dd($holidayData);
        $holiday = new Day();
        if ($holidayData['day_type'] == 'SATURDAY'){
            $holiday->day_type = 'DAY';
        }elseif ($holidayData['day_type'] == 'SUNDAY'){
            $holiday->day_type = 'DAY';
        }else{
            $holiday->day_type = $holidayData['day_type'];
        }
        $holiday->country_id = $hierarchy->country_id;
        $holiday->year = $year;
        $holiday->date = $holidayData['date']->toDateString();
        $holiday->day = $holidayData['date']->format('D');

        if($holidayData['day_type'] === 'HOLIDAY'){
            $holiday->name = 'WEEKEND HOLIDAY';
        }
        $holiday->status = 'ACTIVE';

        // Set holiday type ID only for weekend holidays
        if ($holidayData['day_type'] === 'HOLIDAY') {
            $holiday->holiday_type_id = $holidayTypeWeekend->id;
        }elseif($holidayData['day_type'] === 'SATURDAY'){
            $holiday->holiday_type_id = $dayTypeSaturday->id;
        }elseif ($holidayData['day_type'] === 'SUNDAY'){
            $holiday->holiday_type_id = $dayTypeSunday->id;
        }else{
            $holiday->holiday_type_id = $holidayTypeDay->id;
        }
        $holiday->company_id = $hierarchy->id;
        $holiday->save();
    }


    public function getHolidaysByCompany(Request $request)
    {
        $companyId = $request->id;
        $response = Day::where('company_id', '=', $companyId)->where('day_type','=','HOLIDAY')->get();
//        dd($response);
        return response()->json($response);

    }

    public function changeHolidayStatus($id)
    {
        $data['holiday_details'] = Day::find($id);
//        $data['hierarchies'] =Hierarchy::all();
        $data['holiday_types'] = HolidayType::all();
//        $data['holidays'] = Holiday::where('year','=','2024')->get();
        return view('pages.shifts.upadate-holidays')->with($data);

    }

    public function holidayStatusUpdate(Request $request, Day $holiday)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validate(['holiday_type_id'=>'required','status'=>'required','name'=> 'required']);
            $response = $holiday->update($formData);
            self::Log('Holiday Status Updated', 'Pass');
            DB::commit();
            return response()->json($response);
        }catch (\Exception $e) {
           DB::rollBack();
            self::Log($e->getMessage(), 'Fail');

            return response()->json(['error'=> $e->getMessage()], 500);
        }

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

    public function setHolidayTypes(Request $request)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validate(['type'=>'required']);
            $holidayTypes = new HolidayType($formData);
            $holidayTypes->save();
            self::Log('Holiday Types saved successfully !', 'Pass');
            DB::commit();
            return back()->with('message', 'Holiday Types saved successfully !');
        }catch (\Exception $e){
             DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return back()->with('error','Error Saving Holidays!');
        }

    }


    public function setHolidays()
    {
        $year= Carbon::now()->format('Y');
        $today=Carbon::now()->format('Y-m-d');
        $startOfYear= Carbon::create($year,1,1)->format('Y-m-d');
        $companies = Hierarchy::all();
        $holidays = new Day();


//        if ($startOfYear==$today){
            $weekendDays= $this->getWeekendHolidays();
            $major_holidays = $this->getPublicHolidays('major_holiday');

            foreach ($companies as $company) { // TODO: have to add holidays for each
                foreach ($weekendDays as $weekendDay){
                    dump($weekendDay->format('Y-m-d'));

                }
            }




//        }



    }
    public function getPublicHolidays($holiday)
    {
        $client = new Client([
            'base_uri' => 'https://api.api-ninjas.com/v1/',
        ]);
        $year = Carbon::now()->format('Y');
        $headers = ['X-Api-Key' =>env('NINJA_API_KEY'), 'Content-Type'=>'application/json'];
        $response = $client->request('GET','holidays?country=LK&year='.$year.'&type='.$holiday,['headers'=>$headers,]);
        $body= $response->getBody()->getContents();
        $data = json_decode($body,true);

        return $data;
    }


    public  function updateDay(Request $request)
    {
//        dd($request);
        try {
            DB::beginTransaction();
            $day = Day::where('date', '=', $request->date)->where('company_id', '=', $request->company_id)->first();
//        dd($day);
            $day->day_type = 'HOLIDAY';
        $day->name = $request->name;
        $day->holiday_type_id = $request->holiday_type_id;
        $response = $day->save();
            self::Log('Day Updated', 'Pass');
            DB::commit();
            return response()->json($response);
        }catch (\Exception $e) {
            DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return response()->json(['error'=> $e->getMessage()], 500);
        }


    }



    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $formData = $request->validate(['name'=>'required','date'=>'required','holiday_type_id'=>'required','company_id'=>'required']);
            $holiday = new Day($formData);
            $company = Hierarchy::find($request->company_id);
//            dd($request->company_id);
            $holiday->country_id = $company->country_id;
            $holiday->date = $request->date;
            $holiday->year = Carbon::createFromFormat('Y-m-d',$request->date)->format('Y');
            $holiday->day = Carbon::createFromFormat('Y-m-d',$request->date)->format('D');
            $holiday->day_type = 'HOLIDAY';
            $response=$holiday->save();
            self::Log('Day Stored', 'Pass');

            DB::commit();
            return response()->json($response);
        }catch (\Exception $e) {
            DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return response()->json(['error'=> $e->getMessage()], 500);
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
