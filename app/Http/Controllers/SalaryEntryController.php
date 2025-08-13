<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\SalaryEntry;
use App\Models\TransactionAssign;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SalaryEntryController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['salaryentries'] = SalaryEntry::all();

        return view('pages.payroll.salaryentry')->with($data);
    }

    public function ViewAssignSalaryEntry(){
        return view('pages.payroll.assignsalaryentry');
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
        try{

            $formdata = $request->validate(['salary_entry'=>'required']);

            $mode = $request->hndmode;

            if($mode == "1"){
                $salaryentry = new SalaryEntry();
                $salaryentry->EntryName = $request->salary_entry;
                $salaryentry->EntryType =  $request->salary_entry_type;
                $salaryentry->VariableType =  $request->salary_entry_variable;
                $salaryentry->PAYEEffect = ($request->input('chkPAYEEffect'))?1:0;
                $salaryentry->EPFEffect = ($request->input('chkEPFEffect'))?1:0;
                $salaryentry->LateEffect = ($request->input('chkLateEffect'))?1:0;
                $salaryentry->NopayEffect = ($request->input('chkNoPayEffect'))?1:0;
                $salaryentry->Active = $request->salary_entry_status;
                $salaryentry->save();
            }else{

                $result = DB::table('salary_entry')
                    ->where('id',$request->hndsalaryentryid)
                    ->update([
                        'EntryName' => $request->salary_entry,
                        'EntryType' => $request->salary_entry_type,
                        'VariableType' => $request->salary_entry_variable,
                        'PAYEEffect' => ($request->has('chkPAYEEffect'))?1:0,
                        'EPFEffect' => ($request->has('chkEPFEffect'))?1:0,
                        'LateEffect' => ($request->has('chkLateEffect'))?1:0,
                        'NopayEffect' => ($request->has('chkNoPayEffect'))?1:0,
                        'Active' => $request->salary_entry_status
                    ]);

            }
            self::Log('Salary entry saved', 'Pass');

            return back()->with('message',"Salary entry saved successfully");

        }catch(\Exception $e){
            self::Log($e->getMessage(), 'Fail');

            return back()->with('erromsg',$e->getMessage());

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

    public function ListSalaryEntries(){

    }

    public function GetSalaryEntry(Request $request){

        $data['salaryentries'] = SalaryEntry::where('id',$request->salaryentry_id)->get();

        return $data['salaryentries'];
    }

    public function GetTransactionsByType(Request $request){
        $data['salaryentries'] = SalaryEntry::where('EntryType',$request->typeid)->get();
        return $data['salaryentries'];
    }

    public function AssignedTransaction(Request $request){

        try{
            DB::beginTransaction();
            foreach ($request->items as $item) {

                $modTrAssignCheck = TransactionAssign::updateOrCreate(
                    ['TransactionCode'=>$request->transaction_id,'EmployeeCode'=>$item['value'],'TransactionMonth'=>$request->transaction_month,'TransactionYear'=>$request->transaction_year],
                    ['Amount'=>$request->amount]
                );
            }
            self::Log('Assign Transaction', 'Pass');

            DB::commit();
        }catch(\Exception $e){
            self::Log($e->getMessage(), 'Fail');
            DB::rollBack();
            return response()->json(['error' => $e]);
        }
    }

    public function GetTransactionsById(Request $request){

        $data['salaryentries'] = SalaryEntry::where('id',$request->transaction_id)->get();
        return $data['salaryentries'];
    }
}


