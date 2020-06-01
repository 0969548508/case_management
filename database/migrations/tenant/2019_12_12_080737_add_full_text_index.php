<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullTextIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Full Text Index
        DB::statement('ALTER TABLE users ADD FULLTEXT fulltext_index (name, family_name, middle_name, email)');

        DB::statement('ALTER TABLE emails ADD FULLTEXT fulltext_index (email)');

        DB::statement('ALTER TABLE phones ADD FULLTEXT fulltext_index (phone_number)');

        DB::statement('ALTER TABLE clients ADD FULLTEXT fulltext_index (name, abn)');
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
