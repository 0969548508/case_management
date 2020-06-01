<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		// Run when first deploy
		$this->call([
			addPermissionData::class,
			addSuperAdminRole::class,
			AddEmailSupperAdmin::class,
			PasswordPoliciesTableDataSeeder::class,
            AddCountriesStatesCitiesSeeder::class,
            addNotationCategories::class
        ]);
    }
}
