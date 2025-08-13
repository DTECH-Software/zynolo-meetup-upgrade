<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'User Administration Main Menu', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Add Users', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'View Users', 'guard_name' => 'web', 'level' => 2]);

        Permission::create(['name' => 'Hierarchy Administration', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Add Hierarchy Levels', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Add Hierarchies', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'View Hierarchies', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Add Departments', 'guard_name' => 'web', 'level' => 2]);

        Permission::create(['name' => 'Employee Administration', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Add Details', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Add Employees', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'View Employees', 'guard_name' => 'web', 'level' => 2]);

        Permission::create(['name' => 'Financial Administration', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Add Banks', 'guard_name' => 'web', 'level' => 2]);

        Permission::create(['name' => 'Shift Administration', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Update Calender', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Add Shifts', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Upload Daily Attendance Reports', 'guard_name' => 'web', 'level' => 2]);

        Permission::create(['name' => 'Leave Administration', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Add Leave Details', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Apply Leaves', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Review Leaves', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Apply Short Leaves', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Review Short Leaves', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'View Adjusted Attendance', 'guard_name' => 'web', 'level' => 2]);

        Permission::create(['name' => 'Settings', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'Manage Permissions', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Manage User Groups', 'guard_name' => 'web', 'level' => 2]);
            Permission::create(['name' => 'Access Management', 'guard_name' => 'web', 'level' => 2]);

            Permission::create(['name' => 'Attendance', 'guard_name' => 'web', 'level' => 1]);
            Permission::create(['name' => 'View All Attendance Data', 'guard_name' => 'web', 'level' => 2]);

             Permission::create(['name' => 'Attendance Data', 'guard_name' => 'web', 'level' => 1]);
           Permission::create(['name' => 'Attendance Reports', 'guard_name' => 'web', 'level' => 1]);



        Permission::create(['name' => 'Manage Payroll', 'guard_name' => 'web', 'level' => 1]);


        // TODO: Protect other routes with permissions such as pages.
    }
}
