<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\PayScheme;
use App\Models\EmployeeScheme;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchemaSetupController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.payroll.scheme');
    }

    public function ViewAssign(){
        return view('pages.payroll.assignscheme');
    }

    public function SaveAssign(Request $request){

        try{
            DB::beginTransaction();
            foreach ($request->items as $item) {
                EmployeeScheme::updateOrCreate(
                    ['EmployeeCode' => $item['value']],
                    ['EmployeeCode' => $item['value'], 'SchemCode' => $request->scheme_id]
                );
            }
            DB::commit();
            self::Log('Assigned Successfully', 'Pass');

            return response()->json(['message' => 'Updates Successfully !']);

        }catch(\Exception $e){
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();
            return response()->json(['error' => 'Failed']);
        }

    }

    public function ListSchemes(){
        $paySchemeList = PayScheme::where('status','ACTIVE')->get();
        return  $paySchemeList;
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

            $formdata = $request->validate(['scheme_name'=>'required','epfemployee'=>'required','epfemployer'=>'required','etf'=>'required']);
            /*
            $payscheme = new PayScheme();
            $payscheme->name = $request->scheme_name;
            $payscheme->OTDivider = $request->otdivider;
            $payscheme->NOPAYDivider = $request->nopaydivider;
            $payscheme->EPFEmployee = $request->epfemployee;
            $payscheme->EPFEmployer = $request->epfemployer;
            $payscheme->ETFEmployeer = $request->etf;
            $payscheme->OTEffect = ($request->input('chkOTCalculate'))?1:0;
            $payscheme->NopayEffect = ($request->input('chkNopayEffect'))?1:0;
            $payscheme->fromattenday = $request->fromday;
            $payscheme->toattenday = $request->toattday;

            $payscheme->save();
            */
            $modelPayScheme = PayScheme::updateOrCreate(
                ['id'=>$request->input('schemeid')],
                ['name'=>$request->input('scheme_name'),'OTDivider'=>$request->input('otdivider'),'NOPAYDivider'=>$request->input('otdivider'),
                 'EPFEmployee'=>$request->input('epfemployee'), 'EPFEmployer'=>$request->input('epfemployer'),'ETFEmployeer'=>$request->input('etf'),
                 'OTEffect'=>($request->input('chkOTCalculate'))?1:0, 'NopayEffect'=>($request->input('chkNopayEffect'))?1:0,
                 'fromattenday'=>$request->input('fromday'),'PayeeEffect'=>($request->input('chkPayeeEffect'))?1:0]
            );
            self::Log('Payroll scheme saved', 'Pass');

            return back()->with('message',"Payroll scheme saved successfully");

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

    public function GetSchemeById(string $id){
        $paySchemeList = PayScheme::where('id',$id)->get();
        return  $paySchemeList;
    }
}
