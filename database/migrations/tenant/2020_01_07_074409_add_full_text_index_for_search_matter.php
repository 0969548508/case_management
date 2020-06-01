<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullTextIndexForSearchMatter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE cases ADD FULLTEXT fulltext_index (case_number, last_state)');
        DB::statement('ALTER TABLE types ADD FULLTEXT fulltext_index (name)');
        DB::statement('ALTER TABLE offices ADD FULLTEXT fulltext_index (name, address)');
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
