<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class addSuperAdminRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create role super admin
        $roleSuperAdmin = Role::create(['name' => 'super admin']);

        $permissionList = Permission::pluck('name');

        $roleSuperAdmin->syncPermissions($permissionList);
    }
}
