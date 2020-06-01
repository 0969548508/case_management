<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullTextIndexForSearchUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE roles ADD FULLTEXT fulltext_index (name)');

        DB::statement('ALTER TABLE addresses ADD FULLTEXT fulltext_index (address, country, state, city, postal_code)');

        DB::statement('ALTER TABLE licenses ADD FULLTEXT fulltext_index (country, state, `number`)');
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
