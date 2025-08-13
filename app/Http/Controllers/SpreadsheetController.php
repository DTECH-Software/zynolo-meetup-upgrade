<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankBranch;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\Hierarchy;
use App\Models\JobCategory;
use App\Models\Title;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetController extends Controller
{

    public function importEmployees(Request $request)
    {
        $filePath = $request->file('employee_sheet')->getPathName();
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Specify the number of rows from the view input
        $numRows = $request->input('num_rows');

        try {

            DB::beginTransaction();

        // Loop through rows
        for ($row = 2; $row <= $numRows; $row++) {
//            dump($sheet->getCell("V$row")->getValue());

            $employeeData = [
//                if( $sheet->getCell("A$row")->getValue()){
                    'fingerprint_id' => $sheet->getCell("A$row")->getValue(),
//                }else{
//
//                }
                'first_name' => $sheet->getCell("B$row")->getValue(),
                'last_name' => $sheet->getCell("C$row")->getValue(),
                'full_name' => $sheet->getCell("D$row")->getValue(),
                'title_id' => $this->getForeignKeyId(Title::class, $sheet->getCell("X$row")->getValue()),
                'name_with_initials' => $sheet->getCell("E$row")->getValue(),
                'known_name' => $sheet->getCell("G$row")->getValue(),
                'gender' => $sheet->getCell("I$row")->getValue(),
                'marital_status' => $sheet->getCell("J$row")->getValue(),
                'employment_type_id' => $this->getForeignKeyId(EmploymentType::class, $sheet->getCell("L$row")->getValue()),
                //$formattedDate = Carbon::parse($dateInput)->format('Y-m-d');
//                'date_of_birth' => $sheet->getCell("I$row")->getValue(),
                'date_of_birth' => $sheet->getCell("M$row")->getValue() != null ? Carbon::parse($sheet->getCell("M$row")->getValue())->format('Y-m-d') : '',
                'nic' => $sheet->getCell("N$row")->getValue(),
                'passport' => $sheet->getCell("O$row")->getValue(),
                'religion' => $sheet->getCell("P$row")->getValue(),
                'nationality' => $sheet->getCell("Q$row")->getValue(),
                'bank_id' => $this->getForeignKeyId(Bank::class, $sheet->getCell("R$row")->getValue()),
                'bank_branch_id' => $this->getForeignKeyId(BankBranch::class, $sheet->getCell("S$row")->getValue()),
                'employee_number' => $sheet->getCell("T$row")->getValue(),
                'epf_etf_number' => $sheet->getCell("U$row")->getValue(),
                'company_id' => Hierarchy::where('hierarchy_name',$sheet->getCell("V$row")->getValue())->first()->id,
                'department_id' => $this->getForeignKeyId(Department::class, $sheet->getCell("X$row")->getValue()),
                'current_designation' => $this->getForeignKeyId(Designation::class, $sheet->getCell("Y$row")->getValue()),
                'joined_designation' => $sheet->getCell("Z$row")->getValue() != null ? $this->getForeignKeyId(Designation::class, $sheet->getCell("Z$row")->getValue()):'',
                'job_category_id' => $sheet->getCell("AE$row")->getValue() != null ? $this->getForeignKeyId(JobCategory::class, $sheet->getCell("AE$row")->getValue()):'',

//                'status' => $sheet->getCell("N$row")->getValue(),
                //should convert to the date format
                'date_of_appointment' =>$sheet->getCell("AC$row")->getValue()!= null ? Carbon::parse($sheet->getCell("AC$row")->getValue())->format('Y-m-d'):'' ,
                'confirmation_due' =>$sheet->getCell("AD$row")->getValue() != null ? Carbon::parse($sheet->getCell("AD$row")->getValue())->format('Y-m-d'): '' ,
                'confirmed_on' => $sheet->getCell("AE$row")->getValue() != null ? Carbon::parse($sheet->getCell("AE$row")->getValue())->format('Y-m-d'): '' ,
                'eligibility_for_ot' => $sheet->getCell("AK$row")->getValue() ,
                'basic_salary' => $sheet->getCell("AL$row")->getValue(),

                'res_apartment_building_no' => $sheet->getCell("AM$row")->getValue(),
                'res_street' => $sheet->getCell("AN$row")->getValue(),
                'res_city' => $sheet->getCell("AO$row")->getValue(),
                'res_district' => $sheet->getCell("AP$row")->getValue(),
                'res_province' => $sheet->getCell("AQ$row")->getValue(),
                'res_electorate' => $sheet->getCell("AR$row")->getValue(),

                'per_apartment_building_no' => $sheet->getCell("AT$row")->getValue(),
                'per_street' => $sheet->getCell("AU$row")->getValue(),
                'per_city' => $sheet->getCell("AV$row")->getValue(),
                'per_district' => $sheet->getCell("AW$row")->getValue(),
                'per_province' => $sheet->getCell("AX$row")->getValue(),
                'per_electorate' => $sheet->getCell("AY$row")->getValue(),
                'per_country' => $this->getForeignKeyId(Country::class, $sheet->getCell("AZ$row")->getValue()),
                'contact_number' => $sheet->getCell("BA$row")->getValue(),
                'email_address' => $sheet->getCell("BB$row")->getValue(),
                'emergency_contact_name' => $sheet->getCell("BC$row")->getValue(),
                'emergency_contact_number' => $sheet->getCell("BD$row")->getValue(),
            ];

            //reporting person id should be updated after this , get it by name with initials

            // Set null values where needed
            foreach ($employeeData as $key => $value) {
                if (empty($value)) {
                    $employeeData[$key] = null;
                }
            }

            // Create or update employee
            Employee::updateOrCreate(
                ['fingerprint_id' => $employeeData['fingerprint_id']], // Match by unique field
                $employeeData
            );
        }

        DB::commit();
        return redirect()->back()->with('message', 'Employee data imported successfully.');

        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e);

        }

    }

    private function getForeignKeyId($model, $name)
    {
        return $model::where('name', $name)->value('id');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.employees.upload-employee-details');
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
