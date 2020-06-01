<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullTextIndexForSearchClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE locations ADD FULLTEXT fulltext_index (name, abn)');

        DB::statement('ALTER TABLE contacts_list ADD FULLTEXT fulltext_index (name, job_title, email, phone, mobile, fax, note)');

        DB::statement('ALTER TABLE price_clients ADD FULLTEXT fulltext_index (name, description)');

        DB::statement('ALTER TABLE price_locations ADD FULLTEXT fulltext_index (name, description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
