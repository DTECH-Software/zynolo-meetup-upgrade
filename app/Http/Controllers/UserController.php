<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\PermissionTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use PermissionTrait,AuditLogTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::HandlePermission('View Users');

        $data['roles'] = Role::all();

        return view ('pages.users.add-user')->with($data);
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
        self::HandlePermission('Add Users');

        $request->validate([
            'name' => 'required',
            'email'=>'required|unique:users',
            'password' => ['required','string','min:10','regex:/[a-zA-Z0-9]/','regex:/[@$!%#?&]/'],
            'repassword'=>'required|same:password',
            'employee_id'=>'required',
            'role'=>'required',
        ]);

        try {

         DB::beginTransaction();

         $user = new User();
         $user->name = $request->name;
         $user->password = $request->password;
         $user->email = $request->email;
         $user->employee_id = $request->employee_id;
         $user->save();

         $user->assignRole($request->role); // Assigning role to user

         DB::commit();
            self::Log('User Added', 'Pass');

         return redirect()->route('users.showUsers')->with('message','User Added Successfully !');

        } catch (\Exception $e) {
            self::Log($e->getMessage(), 'Fail');

            DB::rollBack();

//            return  view('pages.users.add-user')->with('errors', $e->getMessage());
            return redirect()->route('users.showUsers')->with('message', $e->getMessage());

        }

    }


    public function showUsers()
    {
        self::HandlePermission('View Users');

        $data['users'] = User::with('roles')->get();

        return view('pages.users.view-users')->with($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        self::HandlePermission('View Users');

        $data['user'] = User::find($id);

        $data['roles'] = Role::all();

        return view('pages.users.edit-user')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // it is in show method
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        self::HandlePermission('Add Users');

        try {
            DB::beginTransaction();

            $form_data = $request->validate([
                              'name'=>'required',
                              'email'=>'required',
                              'role'=>'required',
                              'status'=>'required',
                          ]);

            $user->update($form_data);

            $user->assignRole($request->role);

            DB::commit();

            $data['users'] = User::all();
            self::Log('User Updated', 'Pass');

            return view('pages.users.view-users')->with('message','User Updated Successfully!')->with($data);

      } catch (\Exception $e) {

         DB::rollBack();
            self::Log($e->getMessage(), 'Fail');

         $data['users'] = User::all();

         return view('pages.users.view-users')->with('error','Error Updating User !')->with($data);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
