<?php

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
			addPermissionData::class,
			addSystemAdminRoleForTenant::class,
			AddEmailSupperAdminForTenant::class,
			PasswordPoliciesTableDataSeeder::class,
            addNotationCategories::class
        ]);
    }
}
