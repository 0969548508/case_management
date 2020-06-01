<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class addSystemAdminRoleForTenant extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create role system admin
        $roleSystemAdmin = Role::create(['name' => 'system admin']);

        $permissionList = Permission::pluck('name');

        $roleSystemAdmin->syncPermissions($permissionList);
    }
}
