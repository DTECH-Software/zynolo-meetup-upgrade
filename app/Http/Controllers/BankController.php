<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\Bank;
use App\Models\BankBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('Add Banks');

        $data['banks'] = Bank::all();
//        dd($data);
        return view('pages.payroll.add-banks')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getBranchesById(Request $request)
    {
         $branches = BankBranch::where('bank_id','=',$request->id)->get();

        return response()->json($branches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $fromData = $request->validate(['name'=>'required','abbreviation'=>'required']);
            $bank = new Bank($fromData);
            $bank->save();
            self::Log('Bank Created Successfully', 'Fail');
            DB::commit();
             return back()->with('message','Bank Created Successfully !');
        }catch (\Exception $e) {
             DB::rollBack();
            self::Log($e->getMessage(), 'Fail');
            return  back()->with('error','Error Creating Bank !');
        }

    }


    public function addBranches(Request $request)
    {
        self::HandlePermission('Add Banks');

        try {
            $formData=$request->validate([ 'bank_id'=>'required',
                'name'=>'required',
                'address'=>'required',
                'contact_no'=>'required',
                'branch_code'=>'required',
            ]);

            $bank_branch = new BankBranch($formData);
            $bank_branch->save();
            self::Log('Branches Added Successfully', 'Fail');
            return back()->with('message','Branches Added Successfully');
        }catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');
            return back()->with('error', $e->getMessage());
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
