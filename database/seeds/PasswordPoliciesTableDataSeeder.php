<?php

use Illuminate\Database\Seeder;
use App\Models\PasswordPolicies;

class PasswordPoliciesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	PasswordPolicies::truncate();
        $settings = [
            [
                'id'                     => 1,
                'pass_length'            => 8,
                'special_character'      => 1,
                'capital_letter'         => 1,
                'number'                 => 1,
                'pass_period'            => 30,
                'password_history'       => 6,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
        ];

        PasswordPolicies::insert($settings);
    }
}
