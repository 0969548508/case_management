<?php

use Illuminate\Database\Seeder;

class addNotationCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('notation_categories')->truncate();
    	$categories = [
    		['name' => 'AMH Entry'],
			['name' => 'Audio File'],
			['name' => 'Case Notes'],
			['name' => 'Concilliation'],
			['name' => 'Employment Documents'],
			['name' => 'File Update'],
			['name' => 'General Correspondence'],
			['name' => 'Hearing'],
			['name' => 'I.D. Assist'],
			['name' => 'Interim Report'],
			['name' => 'Interim Report / Extension Request'],
			['name' => 'Investigation Plan'],
			['name' => 'Medical'],
			['name' => 'Photographic Schedule'],
			['name' => 'Police Interview Confirmation'],
			['name' => 'Previous Report'],
			['name' => 'Quality Report from TAC'],
			['name' => 'ROI'],
			['name' => 'Search Request'],
			['name' => 'Search Results'],
			['name' => 'Sketch Plan'],
			['name' => 'Statement'],
			['name' => 'Supplementary Report'],
			['name' => 'Surveillance Logs'],
			['name' => 'Transcript'],
			['name' => 'Video Footage'],
			['name' => 'Investigator Final Report']
		];

        DB::table('notation_categories')->insert($categories);
    }
}
