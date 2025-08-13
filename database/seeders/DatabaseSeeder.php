<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        DB::table('roles')->truncate();

        $roles = new Role();
        $roles->create(['name'=>'Super Admin', 'guard_name'=>'web']);

        $user = new User();
        $user->name = 'Super Admin';
        $user->email = 'superadmin@test.com';
        $user->password = Hash::make('SuperAdmin@test.com#123'); // change the password using UI
        $user->save();

        $user->assignRole('Super Admin');

        $this->call(PermissionsSeeder::class); // call the permissions seeder class

        $permissions = Permission::all()->pluck('name')->toArray();

        $role = Role::findByName('Super Admin');

        $role->syncPermissions($permissions);

        $this->call(HolidayTypesSeeder::class);
        $this->call(CountrySeeder::class);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}