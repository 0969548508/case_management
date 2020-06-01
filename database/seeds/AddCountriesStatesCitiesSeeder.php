<?php

use Illuminate\Database\Seeder;

class AddCountriesStatesCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Eloquent::unguard();
        
		DB::unprepared(file_get_contents(base_path('countries.sql')));
        DB::unprepared(file_get_contents(base_path('states.sql')));
        DB::unprepared(file_get_contents(base_path('cities.sql')));
        DB::unprepared(file_get_contents(base_path('cities1.sql')));
        DB::unprepared(file_get_contents(base_path('cities2.sql')));
        DB::unprepared(file_get_contents(base_path('cities3.sql')));
        DB::unprepared(file_get_contents(base_path('cities4.sql')));
        DB::unprepared(file_get_contents(base_path('cities5.sql')));
        DB::unprepared(file_get_contents(base_path('cities6.sql')));
        DB::unprepared(file_get_contents(base_path('cities7.sql')));
        DB::unprepared(file_get_contents(base_path('cities8.sql')));
        DB::unprepared(file_get_contents(base_path('cities9.sql')));
    }
}
