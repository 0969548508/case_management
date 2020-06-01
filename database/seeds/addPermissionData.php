<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class addPermissionData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_has_permissions')->delete();
        DB::table('permissions')->delete();

        $listData = array(
            // user management
            ['slug' => 'users management', 'name' => 'view users'],
            ['slug' => 'users management', 'name' => 'create users'],
            ['slug' => 'users management', 'name' => 'edit users'],
            ['slug' => 'users management', 'name' => 'delete users'],

            // client management
            ['slug' => 'client management', 'name' => 'view clients and client locations'],
            ['slug' => 'client management', 'name' => 'create clients and client locations'],
            ['slug' => 'client management', 'name' => 'edit client and client locations information'],
            ['slug' => 'client management', 'name' => 'delete clients and client locations'],

            // client contact list
            ['slug' => 'client contact list', 'name' => 'view client contacts'],
            ['slug' => 'client contact list', 'name' => 'create client contacts'],
            ['slug' => 'client contact list', 'name' => 'edit client contacts'],
            ['slug' => 'client contact list', 'name' => 'delete client contacts'],

            // client price list
            ['slug' => 'client price list', 'name' => 'view client price list'],
            ['slug' => 'client price list', 'name' => 'edit client price list'],

            // client location contact list
            ['slug' => 'client location contact list', 'name' => 'view client location contacts'],
            ['slug' => 'client location contact list', 'name' => 'create client location contacts'],
            ['slug' => 'client location contact list', 'name' => 'edit client location contacts'],
            ['slug' => 'client location contact list', 'name' => 'delete client location contacts'],

            // client location agreements
            ['slug' => 'client location agreements', 'name' => 'view agreements'],
            ['slug' => 'client location agreements', 'name' => 'upload agreements'],
            ['slug' => 'client location agreements', 'name' => 'edit agreements'],
            ['slug' => 'client location agreements', 'name' => 'delete agreements'],

            // client location price list
            ['slug' => 'client location price list', 'name' => 'view client location price list'],
            ['slug' => 'client location price list', 'name' => 'edit client location price list'],

            // matter management
            ['slug' => 'matter management', 'name' => 'view matters'],
            ['slug' => 'matter management', 'name' => 'create matters'],
            ['slug' => 'matter management', 'name' => 'edit matters information'],
            ['slug' => 'matter management', 'name' => 'delete matters'],

            ['slug' => 'matter management', 'name' => 'edit matters milestone'],
            ['slug' => 'matter management', 'name' => 'due date'],
            ['slug' => 'matter management', 'name' => 'internal due date'],
            ['slug' => 'matter management', 'name' => 'date received'],
            ['slug' => 'matter management', 'name' => 'date of referral'],
            ['slug' => 'matter management', 'name' => 'date invoiced'],
            ['slug' => 'matter management', 'name' => 'date report sent'],
            ['slug' => 'matter management', 'name' => 'date file returned'],
            ['slug' => 'matter management', 'name' => 'date interim report sent'],

            ['slug' => 'matter management', 'name' => 'edit matter status'],
            ['slug' => 'matter management', 'name' => 'close matter'],

            // matter assignment
            ['slug' => 'matter assignment', 'name' => 'view matter assignments'],
            ['slug' => 'matter assignment', 'name' => 'assign users to matters'],

            // matter notations
            ['slug' => 'matter notations', 'name' => 'view notations'],
            ['slug' => 'matter notations', 'name' => 'upload notations'],
            ['slug' => 'matter notations', 'name' => 'edit notations'],
            ['slug' => 'matter notations', 'name' => 'delete notations'],

            // files and folders
            ['slug' => 'files and folders', 'name' => 'view files and folders'],
            ['slug' => 'files and folders', 'name' => 'upload files and create folders'],
            ['slug' => 'files and folders', 'name' => 'edit files and folders'],
            ['slug' => 'files and folders', 'name' => 'delete files and folders'],

            // expenses
            ['slug' => 'expenses', 'name' => 'view expenses'],
            ['slug' => 'expenses', 'name' => 'create and submit expense'],
            ['slug' => 'expenses', 'name' => 'approve expenses entries'],
            ['slug' => 'expenses', 'name' => 'edit expenses'],
            ['slug' => 'expenses', 'name' => 'delete expenses'],

            // invoice and billing
            ['slug' => 'invoice and billing', 'name' => 'view invoice and billing'],
            ['slug' => 'invoice and billing', 'name' => 'create invoices'],
            ['slug' => 'invoice and billing', 'name' => 'manage payments for invoices'],
            ['slug' => 'invoice and billing', 'name' => 'send invoices'],
            ['slug' => 'invoice and billing', 'name' => 'edit invoices'],
            ['slug' => 'invoice and billing', 'name' => 'delete invoices'],

            // reports
            ['slug' => 'reports', 'name' => 'view invoice and billing reports'],
            ['slug' => 'reports', 'name' => 'create invoices reports'],
            ['slug' => 'reports', 'name' => 'manage payments for invoices reports'],
            ['slug' => 'reports', 'name' => 'send invoices reports'],
            ['slug' => 'reports', 'name' => 'edit invoices reports'],
            ['slug' => 'reports', 'name' => 'delete invoices reports'],

            // roles managements
            ['slug' => 'roles managements', 'name' => 'view roles'],
            ['slug' => 'roles managements', 'name' => 'create roles'],
            ['slug' => 'roles managements', 'name' => 'edit roles'],
            ['slug' => 'roles managements', 'name' => 'delete roles'],

            // rates managements
            ['slug' => 'rates managements', 'name' => 'view rates'],
            ['slug' => 'rates managements', 'name' => 'create rates'],
            ['slug' => 'rates managements', 'name' => 'edit rates'],
            ['slug' => 'rates managements', 'name' => 'delete rates'],

            // offices
            ['slug' => 'offices', 'name' => 'view offices'],
            ['slug' => 'offices', 'name' => 'create offices'],
            ['slug' => 'offices', 'name' => 'edit offices'],
            ['slug' => 'offices', 'name' => 'delete offices'],

            // types subtypes
            ['slug' => 'types subtypes', 'name' => 'view types'],
            ['slug' => 'types subtypes', 'name' => 'create types'],
            ['slug' => 'types subtypes', 'name' => 'edit types'],
            ['slug' => 'types subtypes', 'name' => 'delete types'],

            // audit log
            ['slug' => 'audit log', 'name' => 'allow to view audit log'],

            // password policy
            ['slug' => 'password policy', 'name' => 'allow setting'],
        );

        foreach ($listData as $data) {
	        $permission = Permission::create($data);
        }

        // Assign permission to role if exists
        $listRoles = Role::orderBy('created_at', 'ASC')->pluck('id');
        if (count($listRoles) > 0) {
            $listPermissions = Permission::pluck('name');

            $role = Role::find($listRoles[0]);
            $role->syncPermissions($listPermissions);
        }
    }
}
